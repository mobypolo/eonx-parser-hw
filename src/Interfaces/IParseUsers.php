<?

namespace App\Interfaces;

use App\Entity\ParseDataProvider;

interface IParseUsers
{
    public function setProvider(): static;
    public function askSubmissionIfNew(): static;
    public function askSubmission(ParseDataProvider $provider): void;
    public function performParse(): array;
    public function persist(array $res): void;
}
