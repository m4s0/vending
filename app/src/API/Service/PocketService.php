<?php

declare(strict_types=1);

namespace API\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Core\Domain\Model\Coin\View\Coin;
use Core\Domain\Model\Coin\View\MoneyValue;
use Core\Domain\Model\Coin\View\CoinCollection;

final class PocketService
{
    private $session;

    public function __construct(
        SessionInterface $session
    ) {
        $this->session = $session;
    }

    public function coins(): CoinCollection
    {
        $coins = $this->session->get('pocket', []);
        return new CoinCollection($coins);
    }

    public function reset(): void
    {
        $this->session->set('pocket', []);
    }

    public function insertCoin(Coin $coin): void
    {
        $pocket = $this->coins();
        $pocket->append($coin);
        $this->session->set('pocket', $pocket->getArrayCopy());
    }

    public function returnCoin(Coin $coin): Coin
    {
        $pocket = $this->coins();
        if ($pocket->contains($coin)) {
            $pocket->remove($coin);
            $this->session->set('pocket', $pocket->getArrayCopy());
            return $coin;
        } else {
            throw new \InvalidArgumentException('The coin ' . $coin->value() . ' is not found inside the pocket.');
        }
    }

    public function returnCoins(CoinCollection $coins): CoinCollection
    {
        foreach ($coins as $coin) {
            $this->returnCoin($coin);
        }

        return $coins;
    }

    public function total(): MoneyValue
    {
        return $this->coins()->total();
    }

    public function status(): array
    {
        return [
            'money' => $this->total()->value(),
            'coins' => $this->coins()->values()
        ];
    }

}
