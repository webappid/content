<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-11
 * Time: 06:03
 */

namespace WebAppId\Content\Services\Params;

/**
 * Class AddCategoryParam
 * @package WebAppId\Content\Services\Params
 */
class AddCategoryParam
{
    /**
     * @var string
     */
    public $code;
    /**
     * @var string
     */
    public $name;
    /**
     * @var int
     */
    public $status_id;
    /**
     * @var int
     */
    public $user_id;
    /**
     * @var int|null
     */
    public $parent_id;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getStatusId(): int
    {
        return $this->status_id;
    }

    /**
     * @param int $status_id
     */
    public function setStatusId(int $status_id): void
    {
        $this->status_id = $status_id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return int|null
     */
    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

    /**
     * @param int|null $parent_id
     */
    public function setParentId(?int $parent_id): void
    {
        $this->parent_id = $parent_id;
    }
}