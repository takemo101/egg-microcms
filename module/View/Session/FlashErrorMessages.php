<?php

namespace Module\View\Session;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * 一時エラーメッセージ
 */
final class FlashErrorMessages
{
    public const Key = '__errors__';

    /**
     * @var array<string,string[]>
     */
    private readonly array $messages;

    /**
     * constructor
     *
     * @param FlashBagInterface $flashBag
     */
    public function __construct(
        private readonly FlashBagInterface $flashBag,
    ) {
        $this->messages = $flashBag->get(self::Key, [])[0] ?? [];
    }

    /**
     * エラーメッセージをセッションに保存
     *
     * @param array<string,string[]> $errors
     * @return void
     */
    public function put(array $errors): void
    {
        $this->flashBag->add(self::Key, $errors);
    }

    /**
     * エラーメッセージを全て取得
     *
     * @return array<string,string[]>
     */
    public function all(): array
    {
        return $this->messages;
    }

    /**
     * キーからエラーメッセージを取得
     *
     * @param string $key
     * @return string[]
     */
    public function get(string $key): array
    {
        return $this->messages[$key] ?? [];
    }

    /**
     * キーから最初のエラーメッセージを取得
     *
     * @param string $key
     * @return string|null
     */
    public function first(string $key): ?string
    {
        return $this->messages[$key][0] ?? null;
    }

    /**
     * キーに対するエラーメッセージが存在するか
     *
     * @param string $key
     * @return boolean
     */
    public function has(string $key): bool
    {
        return isset($this->messages[$key]);
    }

    /**
     * エラーメッセージが存在するか
     *
     * @return boolean
     */
    public function isError(): bool
    {
        return count($this->messages) > 0;
    }
}
