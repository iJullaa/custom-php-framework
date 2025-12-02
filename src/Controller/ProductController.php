<?php
namespace App\Controller;

use App\Model\Product;
use App\Service\Router;
use App\Service\Templating;

class ProductController
{
    public function indexAction(Templating $templating, Router $router): ?string
    {
        $products = Product::findAll();
        return $templating->render('product/index.phtml', [
            'products' => $products,
            'router' => $router,
        ]);
    }

    public function createAction(?array $requestPost, Templating $templating, Router $router): ?string
    {
        if ($requestPost) {
            $product = Product::fromArray($requestPost);
            $product->save();
            $router->redirect($router->generatePath('product-index'));
            return null;
        }
        return $templating->render('product/create.phtml', [
            'router' => $router,
        ]);
    }

    public function editAction(int $id, ?array $requestPost, Templating $templating, Router $router): ?string
    {
        $product = Product::find($id);
        if (!$product) {
            die("Product not found");
        }

        if ($requestPost) {
            $product->fill($requestPost);
            $product->save();
            $router->redirect($router->generatePath('product-index'));
            return null;
        }
        return $templating->render('product/edit.phtml', [
            'product' => $product,
            'router' => $router,
        ]);
    }

    public function showAction(int $id, Templating $templating, Router $router): ?string
    {
        $product = Product::find($id);
        if (!$product) {
            die("Product not found");
        }
        return $templating->render('product/show.phtml', [
            'product' => $product,
            'router' => $router,
        ]);
    }

    public function deleteAction(int $id, Router $router): ?string
    {
        $product = Product::find($id);
        if ($product) {
            $product->delete();
        }
        $router->redirect($router->generatePath('product-index'));
        return null;
    }
}