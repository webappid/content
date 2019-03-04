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
    private $title;
    private $code;
    private $description;
    private $keyword;
    private $ogTitle;
    private $ogDescription;
    private $defaultImage;
    private $statusId;
    private $languageId;
    private $publishDate;
    private $additionalInfo;
    private $content;
    private $timeZoneId;
    private $ownerId;
    private $userId;
    private $creatorId;
    private $categories;
    private $parentId;
    private $galleries;
    
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
        return $this->ogTitle;
    }
    
    /**
     * @param string $ogTitle
     */
    public function setOgTitle(string $ogTitle): void
    {
        $this->ogTitle = $ogTitle;
    }
    
    /**
     * @return string|null
     */
    public function getOgDescription(): ?string
    {
        return $this->ogDescription;
    }
    
    /**
     * @param string $ogDescription
     */
    public function setOgDescription(string $ogDescription): void
    {
        $this->ogDescription = $ogDescription;
    }
    
    /**
     * @return int|null
     */
    public function getDefaultImage(): ?int
    {
        return $this->defaultImage;
    }
    
    /**
     * @param int $defaultImage
     */
    public function setDefaultImage(int $defaultImage): void
    {
        $this->defaultImage = $defaultImage;
    }
    
    /**
     * @return int
     */
    public function getStatusId(): ?int
    {
        return $this->statusId;
    }
    
    /**
     * @param int $statusId
     */
    public function setStatusId(int $statusId): void
    {
        $this->statusId = $statusId;
    }
    
    /**
     * @return int
     */
    public function getLanguageId(): ?int
    {
        return $this->languageId;
    }
    
    /**
     * @param int $languageId
     */
    public function setLanguageId(int $languageId): void
    {
        $this->languageId = $languageId;
    }
    
    /**
     * @return string
     */
    public function getPublishDate(): ?string
    {
        return $this->publishDate;
    }
    
    /**
     * @param string $publishDate
     */
    public function setPublishDate(string $publishDate): void
    {
        $this->publishDate = $publishDate;
    }
    
    /**
     * @return string|null
     */
    public function getAdditionalInfo(): ?string
    {
        return $this->additionalInfo;
    }
    
    /**
     * @param string $additionalInfo
     */
    public function setAdditionalInfo(string $additionalInfo): void
    {
        $this->additionalInfo = $additionalInfo;
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
        return $this->timeZoneId;
    }
    
    /**
     * @param int $timeZoneId
     */
    public function setTimeZoneId(int $timeZoneId): void
    {
        $this->timeZoneId = $timeZoneId;
    }
    
    /**
     * @return int
     */
    public function getOwnerId(): ?int
    {
        return $this->ownerId;
    }
    
    /**
     * @param int $ownerId
     */
    public function setOwnerId(int $ownerId): void
    {
        $this->ownerId = $ownerId;
    }
    
    /**
     * @return int
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }
    
    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }
    
    /**
     * @return int
     */
    public function getCreatorId(): ?int
    {
        return $this->creatorId;
    }
    
    /**
     * @param int $creatorId
     */
    public function setCreatorId(int $creatorId): void
    {
        $this->creatorId = $creatorId;
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
        return $this->parentId;
    }
    
    /**
     * @param int $parentId
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
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