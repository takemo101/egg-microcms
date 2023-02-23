<?php

namespace Module\View\Session;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * 一時入力データ
 */
final class FlashOldInputs
{
    public const Key = '__old__';

    /**
     * @var array<string,mixed>
     */
    private readonly array $inputs;

    /**
     * constructor
     *
     * @param FlashBagInterface $flashBag
     */
    public function __construct(
        private readonly FlashBagInterface $flashBag,
    ) {
        $this->inputs = $flashBag->get(self::Key, [])[0] ?? [];
    }

    /**
     * 入力データをセッションに保存
     *
     * @param array<string,mixed> $errors
     * @return void
     */
    public function put(array $errors): void
    {
        $this->flashBag->add(self::Key, $errors);
    }

    /**
     * 入力データを全て削除
     *
     * @return void
     */
    public function clear(): void
    {
        $this->flashBag->set(self::Key, []);
    }

    /**
     * 入力データを全て取得
     *
     * @return array<string,mixed>
     */
    public function all(): array
    {
        return $this->inputs;
    }

    /**
     * キーから入力データを取得
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->inputs[$key] ?? $default;
    }
}
