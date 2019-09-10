<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-02-11
 * Time: 15:20
 */

namespace WebAppId\Content\Services\Params;

/**
 * Class AddContentParam
 * @package WebAppId\Content\Services\Params
 */
class AddContentParam
{
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $code;
    /**
     * @var string
     */
    public $description;
    /**
     * @var string
     */
    public $keyword;
    /**
     * @var string
     */
    public $og_title;
    /**
     * @var string
     */
    public $og_description;
    /**
     * @var int
     */
    public $default_image;
    /**
     * @var int
     */
    public $status_id;
    /**
     * @var int
     */
    public $language_id;
    /**
     * @var string
     */
    public $publish_date;
    /**
     * @var string
     */
    public $additional_info;
    /**
     * @var string
     */
    public $content;
    /**
     * @var int
     */
    public $time_zone_id;
    /**
     * @var int
     */
    public $owner_id;
    /**
     * @var int
     */
    public $user_id;
    /**
     * @var int
     */
    public $creator_id;
    /**
     * @var array
     */
    public $categories;
    /**
     * @var int
     */
    public $parent_id;
    /**
     * @var array
     */
    public $galleries;
    
    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
    
    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
    
    /**
     * @return string
     */
    public function getCode(): ?string
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
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
    
    /**
     * @return string|null
     */
    public function getKeyword(): ?string
    {
        return $this->keyword;
    }
    
    /**
     * @param string $keyword
     */
    public function setKeyword(string $keyword): void
    {
        $this->keyword = $keyword;
    }
    
    /**
     * @return string
     */
    public function getOgTitle(): ?string
    {
        return $this->og_title;
    }
    
    /**
     * @param string $og_title
     */
    public function setOgTitle(string $og_title): void
    {
        $this->og_title = $og_title;
    }
    
    /**
     * @return string|null
     */
    public function getOgDescription(): ?string
    {
        return $this->og_description;
    }
    
    /**
     * @param string $og_description
     */
    public function setOgDescription(string $og_description): void
    {
        $this->og_description = $og_description;
    }
    
    /**
     * @return int|null
     */
    public function getDefaultImage(): ?int
    {
        return $this->default_image;
    }
    
    /**
     * @param int $default_image
     */
    public function setDefaultImage(int $default_image): void
    {
        $this->default_image = $default_image;
    }
    
    /**
     * @return int
     */
    public function getStatusId(): ?int
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
    public function getLanguageId(): ?int
    {
        return $this->language_id;
    }
    
    /**
     * @param int $language_id
     */
    public function setLanguageId(int $language_id): void
    {
        $this->language_id = $language_id;
    }
    
    /**
     * @return string
     */
    public function getPublishDate(): ?string
    {
        return $this->publish_date;
    }
    
    /**
     * @param string $publish_date
     */
    public function setPublishDate(string $publish_date): void
    {
        $this->publish_date = $publish_date;
    }
    
    /**
     * @return string|null
     */
    public function getAdditionalInfo(): ?string
    {
        return $this->additional_info;
    }
    
    /**
     * @param string $additional_info
     */
    public function setAdditionalInfo(string $additional_info): void
    {
        $this->additional_info = $additional_info;
    }
    
    
    /**
     * @return string|null
     */
    
    public function getContent(): ?string
    {
        return $this->content;
    }
    
    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
    
    /**
     * @return int
     */
    public function getTimeZoneId(): ?int
    {
        return $this->time_zone_id;
    }
    
    /**
     * @param int $time_zone_id
     */
    public function setTimeZoneId(int $time_zone_id): void
    {
        $this->time_zone_id = $time_zone_id;
    }
    
    /**
     * @return int
     */
    public function getOwnerId(): ?int
    {
        return $this->owner_id;
    }
    
    /**
     * @param int $owner_id
     */
    public function setOwnerId(int $owner_id): void
    {
        $this->owner_id = $owner_id;
    }
    
    /**
     * @return int
     */
    public function getUserId(): ?int
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
     * @return int
     */
    public function getCreatorId(): ?int
    {
        return $this->creator_id;
    }
    
    /**
     * @param int $creator_id
     */
    public function setCreatorId(int $creator_id): void
    {
        $this->creator_id = $creator_id;
    }
    
    /**
     * @return array
     */
    public function getCategories(): ?array
    {
        return $this->categories;
    }
    
    /**
     * @param array $categories
     */
    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }
    
    /**
     * @return int
     */
    public function getParentId(): ?int
    {
        return $this->parent_id;
    }
    
    /**
     * @param int $parent_id
     */
    public function setParentId(int $parent_id): void
    {
        $this->parent_id = $parent_id;
    }
    
    /**
     * @return array|null
     */
    public function getGalleries(): ?array
    {
        return $this->galleries;
    }
    
    /**
     * @param array $galleries
     */
    public function setGalleries(array $galleries): void
    {
        $this->galleries = $galleries;
    }


}