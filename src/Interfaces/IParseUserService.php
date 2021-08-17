<?

namespace App\Interfaces;

use App\Entity\ParseDataProvider;

interface IParseUserService
{
    public function parse(ParseDataProvider $parse_provider): array;
    public function checkup(): static;
    public function makeRequestByUrl(): static;
    public function prepareUrl(): string;
    public function formatToJson(): static;
    public function getIteratorNode(): static;
    public function perform(): array;
}
