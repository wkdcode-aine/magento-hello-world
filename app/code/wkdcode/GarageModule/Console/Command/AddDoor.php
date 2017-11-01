<?php

namespace wkdcode\GarageModule\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use wkdcode\GarageModule\Model\DoorFactory;
use Magento\Framework\Console\Cli;

class AddDoor extends Command
{
    const INPUT_KEY_NAME = 'name';
    const INPUT_KEY_TYPE = 'type';
    const INPUT_KEY_PRICE = 'price';

    private $doorFactory;

    public function __construct(DoorFactory $doorFactory)
    {
        $this->doorFactory = $doorFactory;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('wkdcode:door:add')
            ->addArgument(
                self::INPUT_KEY_NAME,
                InputArgument::REQUIRED,
                'Item name'
            )->addArgument(
                self::INPUT_KEY_TYPE,
                InputArgument::OPTIONAL,
                'Item type'
            )->addArgument(
                self::INPUT_KEY_PRICE,
                InputArgument::OPTIONAL,
                'Item price'
            );
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $door = $this->doorFactory->create();
        $door->setName($input->getArgument(self::INPUT_KEY_NAME));
        $door->setType($input->getArgument(self::INPUT_KEY_TYPE));
        $door->setPrice($input->getArgument(self::INPUT_KEY_PRICE));
        $door->setIsObjectNew(true);
        $door->save();
        return Cli::RETURN_SUCCESS;
    }
}
