<?php

namespace WebAppId\Content\Presenters;

class ContentPresenter{
    public function store(ContentRequest $request, ContentModel $contentModel, TimeZoneModel $timeZoneModel, ContentCategoryModel $contentCategoryModel, ContentChildModel $contentChildModel)
    {

        $request = $this->getDefault($timeZoneModel, $request);
        
        $result['content'] = $contentModel->addContent($request);

        if(isset($request->parent_id)){
            $contentChildRequest = new \StdClass;
            $contentChildRequest->user_id = $request->user_id;
            $contentChildRequest->content_parent_id = $request->parent_id;
            $contentChildRequest->content_child_id = $result['content']->id;
            $result['content_child'] = $contentChildModel->addContentChild($contentChildRequest);
        }

        $contentCategoryData = new \StdClass;
        $contentCategoryData->user_id = $request->user_id;
        $contentCategoryData->content_id = $result['content']->id;
        $contentCategoryData->categories_id = $request->category_id;
        $result['content_category'] = $contentCategoryModel->addContentCategory($contentCategoryData);
        return $result;
        
    }
}