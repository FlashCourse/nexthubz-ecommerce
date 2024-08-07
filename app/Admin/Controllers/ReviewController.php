<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Carbon;

class ReviewController extends AdminController
{
    protected $title = 'Review';

    protected function grid()
    {
        $grid = new Grid(new Review());

        $grid->quickSearch();

        $grid->column('id', __('Id'))->sortable();
        $grid->column('user_id', __('User'))->display(function ($userId) {
            $user = User::find($userId);
            return $user ? $user->name : 'N/A';
        })->sortable();
        $grid->column('product_id', __('Product'))->display(function ($productId) {
            $product = Product::find($productId);
            return $product ? $product->name : 'N/A';
        })->sortable();
        $grid->column('rating', __('Rating'))->badge('success')->sortable();
        $grid->column('comment', __('Comment'));
        $grid->column('created_at', __('Created at'))->display(function ($createdAt) {
            return Carbon::parse($createdAt)->format('d/m/Y H:i');
        })->sortable();
        $grid->column('updated_at', __('Updated at'))->display(function ($updatedAt) {
            return Carbon::parse($updatedAt)->format('d/m/Y H:i');
        })->sortable();

        $grid->filter(function ($filter) {
            $filter->equal('user_id', 'User')->select(User::all()->pluck('name', 'id'));
            $filter->equal('product_id', 'Product')->select(Product::all()->pluck('name', 'id'));
            $filter->equal('rating', 'Rating');
            $filter->between('created_at', 'Created at')->datetime();
            $filter->between('updated_at', 'Updated at')->datetime();
        });

        $grid->header(function () {
            return 'Review Management';
        });

        $grid->footer(function ($query) {
            return 'Total reviews: ' . $query->count();
        });

        $grid->model()->orderBy('id', 'desc');

        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(Review::findOrFail($id));

        $show->field('id', 'Review ID');
        $show->divider();
        $show->field('user_id', 'User')->as(function ($userId) {
            $user = User::find($userId);
            return $user ? '<a href="/admin/users/' . $userId . '" target="_blank">' . htmlspecialchars($user->name) . '</a>' : 'N/A';
        })->unescape();
        $show->field('product_id', 'Product')->as(function ($productId) {
            $product = Product::find($productId);
            return $product ? '<a href="/admin/products/' . $productId . '" target="_blank">' . htmlspecialchars($product->name) . '</a>' : 'N/A';
        })->unescape();
        $show->divider();
        $show->field('rating', 'Rating')->as(function ($rating) {
            return htmlspecialchars($rating . ' / 5');
        })->badge('success');
        $show->field('comment', 'Comment');
        $show->divider();
        $show->field('created_at', 'Created At')->as(function ($createdAt) {
            return Carbon::parse($createdAt)->format('d/m/Y H:i');
        });
        $show->field('updated_at', 'Updated At')->as(function ($updatedAt) {
            return Carbon::parse($updatedAt)->format('d/m/Y H:i');
        });

        return $show;
    }

    protected function form()
    {
        $form = new Form(new Review());

        $form->select('user_id', __('User'))->options(User::all()->pluck('name', 'id'))->rules('required');
        $form->select('product_id', __('Product'))->options(Product::all()->pluck('name', 'id'))->rules('required');
        $form->number('rating', __('Rating'))->rules('required|integer|min:1|max:5');
        $form->textarea('comment', __('Comment'))->rules('required');

        return $form;
    }
}
