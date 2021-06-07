<?php

namespace App\Controller;

use App\Entity\Bought;
use App\Entity\Cart;
use App\Entity\Review;
use App\Entity\Wishlist;
use App\Repository\AdRepository;
use App\Repository\BoughtRepository;
use App\Repository\CartRepository;
use App\Repository\CategoryRepository;
use App\Repository\CompatibilityRepository;
use App\Repository\DiscountRepository;
use App\Repository\ProductRepository;
use App\Repository\ReviewRepository;
use App\Repository\WishlistRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController extends AbstractController
{

    private $categories;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categories = $categoryRepository->findAll();
    }
    public function homepage(ProductRepository $productRepository)
    {
        $productOfTheDay = $productRepository->findOneBy([
            'id' => 11
        ]);
        $featuredProducts = $productRepository->findFeatured();
        return $this->render("homepage/homepage.html.twig", [
            'categories' => $this->categories,
            'productOfTheDay' => $productOfTheDay,
            'featuredProducts' => $featuredProducts
        ]);
    }
    public function category(string $category, ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $categoryId = $categoryRepository->findOneBy([
            'url' => $category
        ]);
        $products = $productRepository->findBy([
            'category' => $categoryId->getId()
        ]);
        return $this->render("search/search.html.twig", [
            'products' => $products,
            'categories' => $this->categories
        ]);
    }

    public function product(string $product, ProductRepository $productRepository, ReviewRepository $reviewRepository)
    {
        $product = $productRepository->findOneBy([
            'url' => $product
        ]);
        $givenReview = $reviewRepository->findOneBy([
            'product' => $product,
            'owner' => $this->getUser()
        ]);
        return $this->render("product/product.html.twig", [
            'product' => $product,
            'categories' => $this->categories,
            'givenReview' => $givenReview
        ]);
    }

    public function searchEntryPoint(Request $request)
    {
        $term = $request->get('searchTerm');
        if (!$term) {
            return $this->redirectToRoute('homepage');
        }
        return $this->redirectToRoute('search', [
            'searchTerm' => $term
        ]);
    }

    public function search(ProductRepository $productRepository, string $searchTerm)
    {
        $products = $productRepository->findByTerm($searchTerm);
        return $this->render("search/search.html.twig", [
            'products' => $products,
            'categories' => $this->categories
        ]);
    }

    public function reviewNew(Request $request, EntityManagerInterface $em, ProductRepository $productRepository)
    {
        $review = new Review;
        $product = $productRepository->findOneBy([
            'id' =>  $request->get("reviewId")
        ]);
        $review->setOwner($this->getUser());
        $review->setText($request->get('reviewText'));
        $review->setScore($request->get("reviewScore"));
        $review->setProduct($product);
        $em->persist($review);
        $em->flush();
        return $this->redirectToRoute('product', [
            'product' => $product->getUrl()
        ]);
    }
    public function profile(BoughtRepository $boughtRepository)
    {
        $user = $this->getUser();
        $boughtItems = $boughtRepository->findBy([
            'owner' => $user
        ]);
        return $this->render("profile/profile.html.twig", [
            'user' => $user,
            'categories' => $this->categories,
            'boughtItems' => $boughtItems
        ]);
    }
    public function profileChangeName(Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $user->setName($request->get("newName"));
        $em->persist($user);
        $em->flush($user);
        return $this->redirectToRoute('profile');
    }
    public function cart(CartRepository $cartRepository)
    {
        $user = $this->getUser();
        $cartItems = $cartRepository->findBy([
            'owner' => $user
        ]);
        return $this->render("cart/cart.html.twig", [
            'user' => $user,
            'categories' => $this->categories,
            'cartItems' => $cartItems
        ]);
    }
    public function wishlist(WishlistRepository $wishlistRepository)
    {
        $user = $this->getUser();
        $wishlistItems = $wishlistRepository->findBy([
            'owner' => $user
        ]);
        return $this->render("wishlist/wishlist.html.twig", [
            'user' => $user,
            'categories' => $this->categories,
            'wishlistItems' => $wishlistItems
        ]);
    }
    public function cartAdd(Request $request, ProductRepository $productRepository, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $productId = $request->get("productId");
        $product = $productRepository->findOneBy([
            'id' => $productId
        ]);
        $cartItem = new Cart;
        $cartItem->setOwner($user);
        $cartItem->setProduct($product);
        $em->persist($cartItem);
        $em->flush();
        return $this->redirectToRoute('cart');
    }
    public function wishlistAdd(Request $request, ProductRepository $productRepository, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $productId = $request->get("productId");
        $product = $productRepository->findOneBy([
            'id' => $productId
        ]);
        $wishlistItem = new Wishlist;
        $wishlistItem->setOwner($user);
        $wishlistItem->setProduct($product);
        $em->persist($wishlistItem);
        $em->flush();
        return $this->redirectToRoute('wishlist');
    }
    public function cartRemove(Request $request, ProductRepository $productRepository, EntityManagerInterface $em, CartRepository $cartRepository)
    {
        $cartId = $request->get("cartId");
        $cartItem = $cartRepository->findOneBy([
            'id' => $cartId
        ]);
        $em->remove($cartItem);
        $em->flush();
        return $this->redirectToRoute('cart');
    }
    public function wishlistRemove(Request $request, ProductRepository $productRepository, EntityManagerInterface $em, WishlistRepository $wishlistRepository)
    {
        $wishlistId = $request->get("wishlistId");
        $wishlistItem = $wishlistRepository->findOneBy([
            'id' => $wishlistId
        ]);
        $em->remove($wishlistItem);
        $em->flush();
        return $this->redirectToRoute('wishlist');
    }
    public function cartBuy(CartRepository $cartRepository, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $cartItems = $cartRepository->findBy(['owner' => $user]);
        foreach ($cartItems as $cartItem) {
            $bought = new Bought;
            $bought->setOwner($user);
            $bought->setProduct($cartItem->getProduct());
            $bought->setDatetime(new DateTime());
            $em->persist($bought);
            $em->remove($cartItem);
        }
        $em->flush();
        return $this->redirectToRoute('profile');
    }

    public function cartCompatibility(Request $request, CompatibilityRepository $compatibilityRepository)
    {
        $prod1Id = $request->get('prod1');
        $prod2Id = $request->get('prod2');
        return new Response($compatibilityRepository->checkCompatibility($prod1Id, $prod2Id)['code']);
    }
    public function wishlistCount(WishlistRepository $wishlistRepository, DiscountRepository $discountRepository)
    {
        $user = $this->getUser();
        $wishlistItems = $wishlistRepository->findBy([
            'owner' => $user
        ]);
        $products = [];
        foreach ($wishlistItems as $wishlistItem) {
            $products[] = $wishlistItem->getProduct();
        }
        $discountedCount = 0;
        foreach ($products as $product) {
            $discountedProduct = $discountRepository->findOneBy([
                'product' => $product
            ]);
            if ($discountedProduct) {
                $discountedCount++;
            }
        }
        return new JsonResponse([
            'count' => $discountedCount
        ]);
    }
    public function adRandom(AdRepository $adRepository)
    {
        $ads = $adRepository->findAll();
        shuffle($ads);
        return new JsonResponse([
            'image' => $ads[0]->getImage()
        ]);
    }
}
