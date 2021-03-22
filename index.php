<?php
ob_start();

require __DIR__ . "/vendor/autoload.php";

/**
 * BOOTSTRAP
 */

use CoffeeCode\Router\Router;
use Source\Core\Session;

$session = new Session();
$route = new Router(url(), ":");
$route->namespace("Source\App");

/**
 * WEB ROUTES
 */
$route->group(null);
$route->get("/", "Web:home");
$route->get("/sobre", "Web:about");

//blog
$route->get("/blog", "Web:blog");
$route->get("/blog/p/{page}", "Web:blog");
$route->get("/blog/{uri}", "Web:blogPost");
$route->post("/blog/buscar", "Web:blogSearch");
$route->get("/blog/buscar/{search}/{page}", "Web:blogSearch");
$route->get("/blog/em/{category}", "Web:blogCategory");
$route->get("/blog/em/{category}/{page}", "Web:blogCategory");

//auth
$route->group(null);
$route->get("/entrar", "Web:login");
$route->post("/entrar", "Web:login");
$route->get("/cadastrar", "Web:register");
$route->post("/cadastrar", "Web:register");
$route->get("/recuperar", "Web:forget");
$route->post("/recuperar", "Web:forget");
$route->get("/recuperar/{code}", "Web:reset");
$route->post("/recuperar/resetar", "Web:reset");

//optin
$route->group(null);
$route->get("/confirma", "Web:confirm");
$route->get("/obrigado/{email}", "Web:success");

//services
$route->group(null);
$route->get("/termos", "Web:terms");

/**
 * APP
 */
$route->get("/app", "App:home");
$route->get("/app/receber", "App:income");
$route->get("/app/receber/{status}/{category}/{date}", "App:income");
$route->get("/app/pagar", "App:expense");
$route->get("/app/pagar/{status}/{category}/{date}", "App:expense");
$route->get("/app/fixas", "App:fixed");
$route->get("/app/carteiras", "App:wallets");
$route->get("/app/fatura/{invoice}", "App:invoice");
$route->get("/app/perfil", "App:profile");
$route->get("/app/assinatura", "App:signature");
$route->get("/app/sair", "App:logout");

$route->post("/app/dash", "App:dash");
$route->post("/app/launch", "App:launch");
$route->post("/app/invoice/{invoice}", "App:invoice");
$route->post("/app/remove/{invoice}", "App:remove");
$route->post("/app/support", "App:support");
$route->post("/app/onpaid", "App:onpaid");
$route->post("/app/filter", "App:filter");
$route->post("/app/profile", "App:profile");
$route->post("/app/wallets/{wallet}", "App:wallets");

/**
 * ADMIN ROUTES
 */
$route->namespace("Source\App\Admin");

//login
$route->get("/admin", "Login:root");
$route->get("/admin/login", "Login:login");
$route->post("/admin/login", "Login:login");

//register
$route->get("/admin/register", "Register:register");
$route->post("/admin/register", "Register:register");

//dash
$route->get("/admin/dash", "Dash:dash");
$route->get("/admin/dash/home", "Dash:home");
$route->post("/admin/dash/home", "Dash:home");
$route->get("/admin/logoff", "Dash:logoff");
$route->get("/admin/redirectkb", "Dash:redirectKanboard");

//control
$route->get("/admin/control/home", "Control:home");
$route->get("/admin/control/subscriptions", "Control:subscriptions");
$route->post("/admin/control/subscriptions", "Control:subscriptions");
$route->get("/admin/control/subscriptions/{search}/{page}", "Control:subscriptions");
$route->get("/admin/control/subscription/{id}", "Control:subscription");
$route->post("/admin/control/subscription/{id}", "Control:subscription");
$route->get("/admin/control/plans", "Control:plans");
$route->get("/admin/control/plans/{page}", "Control:plans");
$route->get("/admin/control/plan", "Control:plan");
$route->post("/admin/control/plan", "Control:plan");
$route->get("/admin/control/plan/{plan_id}", "Control:plan");
$route->post("/admin/control/plan/{plan_id}", "Control:plan");

//blog
$route->get("/admin/blog/home", "Blog:home");
$route->post("/admin/blog/home", "Blog:home");
$route->get("/admin/blog/home/{search}/{page}", "Blog:home");
$route->get("/admin/blog/post", "Blog:post");
$route->post("/admin/blog/post", "Blog:post");
$route->get("/admin/blog/post/{post_id}", "Blog:post");
$route->post("/admin/blog/post/{post_id}", "Blog:post");
$route->get("/admin/blog/categories", "Blog:categories");
$route->get("/admin/blog/categories/{page}", "Blog:categories");
$route->get("/admin/blog/category", "Blog:category");
$route->post("/admin/blog/category", "Blog:category");
$route->get("/admin/blog/category/{category_id}", "Blog:category");
$route->post("/admin/blog/category/{category_id}", "Blog:category");

//faqs
$route->get("/admin/faq/home", "Faq:home");
$route->get("/admin/faq/home/{page}", "Faq:home");
$route->get("/admin/faq/channel", "Faq:channel");
$route->post("/admin/faq/channel", "Faq:channel");
$route->get("/admin/faq/channel/{channel_id}", "Faq:channel");
$route->post("/admin/faq/channel/{channel_id}", "Faq:channel");
$route->get("/admin/faq/question/{channel_id}", "Faq:question");
$route->post("/admin/faq/question/{channel_id}", "Faq:question");
$route->get("/admin/faq/question/{channel_id}/{question_id}", "Faq:question");
$route->post("/admin/faq/question/{channel_id}/{question_id}", "Faq:question");

//users
$route->get("/admin/users/home", "Users:home");
$route->post("/admin/users/home", "Users:home");
$route->get("/admin/users/home/{search}/{page}", "Users:home");
$route->get("/admin/users/user", "Users:user");
$route->post("/admin/users/user", "Users:user");
$route->get("/admin/users/user/{user_id}", "Users:user");
$route->post("/admin/users/user/{user_id}", "Users:user");

//notification center
$route->post("/admin/notifications/count", "Notifications:count");
$route->post("/admin/notifications/list", "Notifications:list");

//END ADMIN
$route->namespace("Source\App");

/**
 * PAY ROUTES
 */
$route->group("/pay");
$route->post("/create", "Pay:create");
$route->post("/update", "Pay:update");

/**
 * ERROR ROUTES
 */
$route->group("/ops");
$route->get("/{errcode}", "Web:error");

/**
 * ROUTE
 */
$route->dispatch();

/**
 * ERROR REDIRECT
 */
if ($route->error()) {
    $route->redirect("/ops/{$route->error()}");
}

ob_end_flush();