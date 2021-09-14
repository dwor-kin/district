<?php
namespace App\Command;

use App\Service\Migration\DistrictDbMigration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParseDistrictCommand extends Command
{
    protected static $defaultName = 'app:migrate-district';

    /** @var DistrictDbMigration */
    private $districtDbMigration;

    public function __construct(DistrictDbMigration $districtDbMigration)
    {
        $this->districtDbMigration = $districtDbMigration;
        parent::__construct();
    }
    protected function configure()
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Migrate district from selected city')
            ->addArgument('cityName', InputArgument::REQUIRED, 'City name for parse');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $cityName = $input->getArgument('cityName');
            $this->districtDbMigration->migrateFromTheCity([$cityName]);
            
            return 1;
            
        } catch (\Exception $e) {
            echo $e->getMessage();
            
            return 0;
        }
    }
}
