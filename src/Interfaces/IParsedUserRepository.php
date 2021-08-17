<?

namespace App\Interfaces;

use App\Entity\ParsedUser;

interface IParsedUserRepository
{
    public function createOrUpdate(ParsedUser $model): void;
}
