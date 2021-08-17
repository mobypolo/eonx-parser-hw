<?

namespace App\Interfaces;

use App\Entity\ParseDataProvider;

interface IParseDataProviderRepository
{
    public function updateOrCreate(array $array);
    public function updateSubmission(array $array);
    public function save();
}
