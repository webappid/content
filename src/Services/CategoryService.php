<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

namespace WebAppId\Content\Services;

use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use WebAppId\Content\Models\Category;
use WebAppId\Content\Repositories\CategoryRepository;
use WebAppId\Content\Requests\CategoryRequest;
use WebAppId\Content\Responses\CategorySearchResponse;

/**
 * Class CategoryService
 * @package WebAppId\Content\Services
 */
class CategoryService
{
    
    private $user_id;
    private $container;
    
    /**
     * CategoryService constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->user_id = Auth::id() == null ? session('user_id') : Auth::id();
        $this->container = $container;
    }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest $request
     * @param CategoryRepository $categoryRepository
     * @return Category|null
     */
    public function store(CategoryRequest $request,
                          CategoryRepository $categoryRepository): ?Category
    {
        $request->user_id = $this->user_id;
        
        $request->code = str::slug($request->name);
    
        return $this->container->call([$categoryRepository, 'addCategory'], ['data' => $request]);
    }
    
    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param CategoryRepository $categoryRepository
     * @param CategorySearchResponse $categorySearchResponse
     * @return CategorySearchResponse|null
     */
    public function show(Request $request,
                         CategoryRepository $categoryRepository,
                         CategorySearchResponse $categorySearchResponse): ?CategorySearchResponse
    {
        $column = array(
            0 => 'id',
            1 => 'code',
            2 => 'name',
        );
    
        if (isset($request->draw)) {
            $search = $request->search['value'];
            $limit_start = $request->start;
            $limit_length = $request->length;
            $order_column = $column[$request->order[0]['column']];
            $order_dir = $request->order[0]['dir'];
        } else {
            $search = $request->search['value'];
            $limit_start = 0;
            $limit_length = 10;
            $order_column = $column[0];
            $order_dir = 'asc';
        }
    
    
        $data = $this->container->call([$categoryRepository, 'getDatable'], ['search' => $search, 'order_column' => $order_column, 'order_dir' => $order_dir, 'limit_start' => $limit_start, 'limit_length' => $limit_length]);
        $categorySearchResponse->setData($data);
    
        $recordsTotal = $this->container->call([$categoryRepository, 'getAllCount']);
        $categorySearchResponse->setRecordsTotal($recordsTotal);
    
        $recordsFiltered = $this->container->call([$categoryRepository, 'getSearchCount'], ['search', $request->search['value']]);
        $categorySearchResponse->setRecordsFiltered($recordsFiltered);
        return $categorySearchResponse;
    }
    
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @param CategoryRepository $categoryRepository
     * @return Category|null
     */
    public function edit(int $id,
                         CategoryRepository $categoryRepository): ?Category
    {
        $result['category'] = $this->container->call([$categoryRepository, 'getOne'], ['id' => $id]);
        
        return $result;
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param CategoryRequest $request
     * @param CategoryRepository $categoryRepository
     * @return Category|null
     */
    public function update(int $id,
                           CategoryRequest $request,
                           CategoryRepository $categoryRepository): ?Category
    {
    
        $request->user_id = Auth::id();
    
        $request->code = str::slug($request->name);
    
        return $this->container->call([$categoryRepository, 'updateCategory'], ['request' => $request, 'id' => $id]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @param CategoryRepository $categoryRepository
     * @return bool
     */
    public function destroy(int $id,
                            CategoryRepository $categoryRepository): bool
    {
    
        return $this->container->call([$categoryRepository, 'deleteCategory'], ['id' => $id]);
        
    }
}