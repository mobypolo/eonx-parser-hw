<?php

namespace App\Command;

use App\Interfaces\IParseUsers;
use App\Entity\ParseDataProvider;
use App\Interfaces\IParseUserService;
use App\Interfaces\IParsedUserRepository;
use Symfony\Component\Console\Command\Command;
use App\Interfaces\IParseDataProviderRepository;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:parse-users',
    description: 'Parse Users from fake api for Eonx',
)]
/**
 * CLI implementation of parser
 */
class ParseUsersCommand extends Command implements IParseUsers
{

    private ?InputInterface $input;
    private ?OutputInterface $output;

    public function __construct(
        private IParseDataProviderRepository $providers_repo,
        private IParsedUserRepository $users_repo,
        private IParseUserService $parser,
    ) {
        parent::__construct();
    }

    /**
     * Configure arguments for command, all argumants is optional and has default values
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->addArgument('url', InputArgument::OPTIONAL, 'Url of provider, if your url has ampersand, please, pass whole url string in quotes')
            ->addArgument('elements', InputArgument::OPTIONAL, 'Count of elements to try fetch, but not less than one page has')
            ->addArgument('rootIteratorElement', InputArgument::OPTIONAL, 'Root element in JSON with data');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $this->input = $input;
        $this->output = $output;

        /** 
         * After command starts it check specified arguments, if need create submission compares and perform parse
         */
        $res = $this->setProvider()->askSubmissionIfNew()->performParse();

        !empty($res) ? $this->persist($res) : $this->output->writeln('Endpoint didnt return suitable data');

        $this->output->writeln('Task is completed');

        return 1;
    }


    /**
     * setProvider - fetch from db or create and store instance of Data Provider
     *
     * @return static
     */
    public function setProvider(): static
    {
        $url = $this->input->getArgument('url') ?? 'https://randomuser.me/api?nat=AU&results=10';
        $elementsForParse = $this->input->getArgument('elements') ?? 50;
        $rootIteratorElement = $this->input->getArgument('rootIteratorElement') ?? 'results';

        $this->providerModel = $this->providers_repo->updateOrCreate(compact(
            'url',
            'elementsForParse',
            'rootIteratorElement',
        ));

        return $this;
    }

    /**
     * askSubmissionIfNew - create submission compares tour for new url that absent in db
     *
     * @return static
     */
    public function askSubmissionIfNew(): static
    {
        if ($this->providerModel->isNew)
            $this->askSubmission($this->providerModel);

        return $this;
    }

    /**
     * askSubmission - create submission tour, collect data and store it in db
     *
     * @param  mixed $provider
     * @return void
     */
    public function askSubmission(ParseDataProvider $provider): void
    {
        $helper = $this->getHelper('question');

        $this->output->writeln("
            Specify next params, one column maybe consist from multiple properties\n
            Separate properties by ';' (avoid tralling semicolumn).\n
            If Column has value in struction, use '.' separator. (avoid tralling dot)\n
            Total example maybe like: name.title;name.first;name.last
        ");

        $storeAnswers = [];
        foreach (ParseDataProvider::PREDEFINED_QUESTIONS as $value) {
            $question = new Question("Provide property name from api for $value:\n", false);
            $storeAnswers[$value] = $helper->ask($this->input, $this->output, $question);
        }

        $this->providers_repo->updateSubmission($storeAnswers);
    }

    /**
     * performParse - method that start parse, normally fired if all data is exist and loaded
     *
     * @return array
     */
    public function performParse(): array
    {
        return $this->parser->parse($this->providerModel);
    }

    /**
     * persist - store fetched users to db
     *
     * @param  mixed $res
     * @return void
     */
    public function persist(array $res): void
    {
        foreach ($res as $value) {
            $this->users_repo->createOrUpdate($value);
        }
        $this->output->writeln(sprintf('Succefully parsed and store %d elements', count($res)));
    }
}

// https://reqres.in/api/users
// https://randomuser.me/api?nat=AU&results=10