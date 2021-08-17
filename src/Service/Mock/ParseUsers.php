<?php

namespace App\Service\Mock;

use \GuzzleHttp\Client;
use App\Entity\ParsedUser;
use PhpParser\Node\Stmt\While_;
use App\Entity\ParseDataProvider;
use App\Exceptions\AppMainException;
use App\Interfaces\IParseUserService;
use GuzzleHttp\Psr7\Response;

/**
 * ParseUsers - parser logic, may user in any part of app
 */
class ParseUsers implements IParseUserService
{
    private ?ParseDataProvider $parse_provider = null;
    private Response|array|string|null $response;
    private array $iterator = [];
    private bool $endReached = false;
    private int $currentPage = 1;

    public function __construct(
        private Client $client,
    ) {
    }


    /**
     * parse - loop parcer fitting to paginated data
     *
     * @param  mixed $parse_provider
     * @return array
     */
    public function parse(ParseDataProvider $parse_provider): array
    {
        $this->parse_provider = $parse_provider;

        do {
            $this
                ->checkup()
                ->makeRequestByUrl()
                ->formatToJson()
                ->getIteratorNode();
        } while (!(count($this->iterator) >= $this->parse_provider->getElementsForParse() || $this->endReached === true));

        return $this->perform();
    }

    /**
     * checkup - checking supplied data for fitting to requements
     *
     * @return static
     * @throws AppMainException if invalid data specified
     */
    public function checkup(): static
    {
        if (
            !strlen($this->parse_provider->getUrl())
            && !count($this->getSubmission())
        )
            throw new AppMainException("Invalid data specified to " . __METHOD__);

        return $this;
    }

    /**
     * makeRequestByUrl - send request to 3rd party endpoint via Guzzle (curl) library
     *
     * @return static
     * @throws AppMainException if invalid status code reached from 3rd party api
     */
    public function makeRequestByUrl(): static
    {
        $this->response = file_get_contents("/app/dummy_data/parse_user_api_dummny.txt");

        return $this;
    }

    /**
     * prepareUrl - analyzing and recreating endpoint query data, useful for paginated endpoints
     *
     * @return string
     */
    public function prepareUrl(): string
    {
        $url = $this->parse_provider->getUrl();
        $partsOfUrl = parse_url($url);

        if (isset($partsOfUrl['query'])) {
            $url = str_replace($partsOfUrl['query'], "", $url);

            $proxyPartOfUrl = explode("&", $partsOfUrl['query']);
            $partsOfUrl = [];
            foreach ($proxyPartOfUrl as $value) {
                $value = explode("=", $value);
                $partsOfUrl[$value[0]] = $value[1];
            }
        } else {
            $partsOfUrl = [];
        }


        $partsOfUrl = array_merge($partsOfUrl, [
            'page' => $this->currentPage++,
        ]);

        $url = $url . "?" . http_build_query($partsOfUrl);

        return $url;
    }

    /**
     * formatToJson - convert json string to php array by spl
     *
     * @return static
     * @throws AppMainException if invalid response from 3rd party endpoint has reached (if reached not valid json)
     */
    public function formatToJson(): static
    {
        $this->response = json_decode($this->response, true); //!TODO: подключить сюда генератор

        if ($this->response === null)
            throw new AppMainException("Invalid response for specified url at " . __METHOD__);

        return $this;
    }

    /**
     * getIteratorNode - by specified in DataProvider Entity find roots and concat paginated data
     *
     * @return static
     */
    public function getIteratorNode(): static
    {
        $results = $this->response[$this->parse_provider->getRootIteratorElement()] ?? [];
        if (count($results) == 0) $this->endReached = true;

        $this->iterator = [...$this->iterator, ...$results];

        return $this;
    }

    /**
     * perform - mapping data from json to app app architecture
     *
     * @return array
     */
    public function perform(): array
    {
        $result = [];
        foreach ($this->iterator as $user) {
            $prepareModel = new ParsedUser();
            foreach ($this->parse_provider->getSubmission() as $key => $field) {
                //TODO: if has error - offer to make amend
                if (!method_exists($prepareModel, "set$key"))
                    continue;

                $field = explode(';', $field);

                $resultRow = [];
                foreach ($field as $attribution) {
                    $attribution = explode('.', $attribution);
                    $link = $user;
                    foreach ($attribution as $attr) {
                        $link = $link[$attr] ?? '';
                    }
                    $resultRow[] = $link;
                }

                $prepareModel->{"set$key"}(implode(" ", $resultRow));
            }
            if (!$prepareModel->isEmptyModel())
                $result[] = $prepareModel;
        }

        return $result;
    }
}
