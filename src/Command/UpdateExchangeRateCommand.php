<?php

namespace App\Command;

use App\Enum\CurrencyEnum;
use App\Repository\ExchangeRateRepository;
use App\Service\ExchangeRate\ExchangeRateService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateExchangeRateCommand extends Command
{
    public function __construct(
        private readonly ExchangeRateRepository $exchangeRateRepository,
        private readonly ExchangeRateService $exchangeRateService
    ) {
        parent::__construct('app:update-exchange-rate');
    }

    protected function configure(): void
    {
        $this->addOption('from', mode: InputOption::VALUE_REQUIRED, description: 'Currency from');
        $this->addOption('to', mode: InputOption::VALUE_REQUIRED, description: 'Currency to');
        $this->addOption('rate', mode: InputOption::VALUE_REQUIRED, description: 'Exchange rate');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $from = CurrencyEnum::from($input->getOption('from'));
        $to = CurrencyEnum::from($input->getOption('to'));
        $rate = round((float)$input->getOption('rate'), 2);

        $exchangeRate = $this->exchangeRateRepository->findOneBy([
            'fromCurrency' => $from,
            'toCurrency' => $to
        ]);

        if ($exchangeRate) {
            $this->exchangeRateService->updateRate($exchangeRate, $rate);
        } else {
            $this->exchangeRateService->create($from, $to, $rate);
        }

        $output->writeln("Exchange rate from {$from->value} to {$to->value} updated to $rate.");

        return Command::SUCCESS;
    }
}
