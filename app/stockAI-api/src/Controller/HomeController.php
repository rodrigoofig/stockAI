<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        // Verifica conexão com o banco de dados
        try {
            $connection = $this->entityManager->getConnection();
            $connection->connect();
            $dbStatus = $connection->isConnected() ? 'connected' : 'disconnected';
            $dbStatusClass = $connection->isConnected() ? 'success' : 'danger';
        } catch (\Exception $e) {
            $dbStatus = 'error: ' . $e->getMessage();
            $dbStatusClass = 'danger';
        }

        $html = <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>StockAI API Documentation</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <style>
                body {
                    background-color: #f8f9fa;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                }
                .header {
                    background: linear-gradient(135deg, #6c5ce7 0%, #3498db 100%);
                    color: white;
                    padding: 3rem 0;
                    margin-bottom: 2rem;
                }
                .endpoint-card {
                    transition: transform 0.3s;
                    margin-bottom: 1.5rem;
                    border: none;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                }
                .endpoint-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 8px 15px rgba(0,0,0,0.1);
                }
                .status-badge {
                    font-size: 0.8rem;
                    padding: 0.35rem 0.65rem;
                    border-radius: 0.25rem;
                }
                .method-get {
                    background-color: #61affe;
                    color: white;
                }
                .method-post {
                    background-color: #49cc90;
                    color: white;
                }
                .method-put {
                    background-color: #fca130;
                    color: white;
                }
                .method-delete {
                    background-color: #f93e3e;
                    color: white;
                }
                .footer {
                    background-color: #343a40;
                    color: white;
                    padding: 2rem 0;
                    margin-top: 3rem;
                }
                .api-status {
                    font-size: 0.9rem;
                }
                .example-request {
                    background-color: #f8f9fa;
                    padding: 1rem;
                    border-radius: 0.25rem;
                    font-family: monospace;
                    font-size: 0.9rem;
                }
            </style>
        </head>
        <body>
            <div class="header text-center">
                <div class="container">
                    <h1 class="display-4"><i class="fas fa-robot me-2"></i>StockAI API</h1>
                    <p class="lead">Complete API for inventory management, orders, suppliers and products</p>
                    <div class="api-status d-inline-block px-3 py-2 bg-dark bg-opacity-25 rounded">
                        <span class="me-3">API Status: <span class="badge bg-success">Online</span></span>
                        <span>Database: <span class="badge bg-{$dbStatusClass}">{$dbStatus}</span></span>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info">
                            <h4><i class="fas fa-info-circle me-2"></i>Getting Started</h4>
                        </div>
                    </div>
                </div>

                <h2 class="mb-4"><i class="fas fa-list-alt me-2"></i>Available Endpoints</h2>

                <div class="row">
                    <!-- Products -->
                    <div class="col-md-6">
                        <div class="card endpoint-card">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0"><i class="fas fa-box me-2"></i>Products</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge method-get">GET</span>
                                    <code><a href="/api/products" target="_blank">/api/products</a></code>
                                </div>
                                <p>Retrieve all products</p>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge method-get">GET</span>
                                    <code><a href="/api/products/1" target="_blank">/api/products/{id}</a></code>
                                </div>
                                <p>Get a specific product</p>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge method-post">POST</span>
                                    <code>/api/products</code>
                                </div>
                                <p>Create a new product</p>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge method-put">PUT</span>
                                    <code>/api/products/{id}</code>
                                </div>
                                <p>Update a product</p>

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge method-delete">DELETE</span>
                                    <code>/api/products/{id}</code>
                                </div>
                                <p class="mb-0">Delete a product</p>
                            </div>
                        </div>
                    </div>

                    <!-- Orders -->
                    <div class="col-md-6">
                        <div class="card endpoint-card">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Orders</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge method-get">GET</span>
                                    <code><a href="/api/orders" target="_blank">/api/orders</a></code>
                                </div>
                                <p>Retrieve all orders</p>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge method-get">GET</span>
                                    <code><a href="/api/orders/1" target="_blank">/api/orders/{id}</a></code>
                                </div>
                                <p>Get a specific order</p>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge method-post">POST</span>
                                    <code>/api/orders</code>
                                </div>
                                <p>Create a new order</p>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge method-put">PUT</span>
                                    <code>/api/orders/{id}</code>
                                </div>
                                <p>Update an order</p>

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge method-delete">DELETE</span>
                                    <code>/api/orders/{id}</code>
                                </div>
                                <p class="mb-0">Delete an order</p>
                            </div>
                        </div>
                    </div>

                    <!-- Suppliers -->
                    <div class="col-md-6">
                        <div class="card endpoint-card">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Suppliers</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge method-get">GET</span>
                                    <code><a href="/api/suppliers" target="_blank">/api/suppliers</a></code>
                                </div>
                                <p>Retrieve all suppliers</p>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge method-get">GET</span>
                                    <code><a href="/api/suppliers/1" target="_blank">/api/suppliers/{id}</a></code>
                                </div>
                                <p>Get a specific supplier</p>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge method-post">POST</span>
                                    <code>/api/suppliers</code>
                                </div>
                                <p>Create a new supplier</p>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge method-put">PUT</span>
                                    <code>/api/suppliers/{id}</code>
                                </div>
                                <p>Update a supplier</p>

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge method-delete">DELETE</span>
                                    <code>/api/suppliers/{id}</code>
                                </div>
                                <p class="mb-0">Delete a supplier</p>
                            </div>
                        </div>
                    </div>

                    <!-- Stock -->
                    <div class="col-md-6">
                        <div class="card endpoint-card">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0"><i class="fas fa-warehouse me-2"></i>Stock</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge method-get">GET</span>
                                    <code><a href="/api/stocks" target="_blank">/api/stocks</a></code>
                                </div>
                                <p>Retrieve all stock items</p>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge method-get">GET</span>
                                    <code><a href="/api/stocks/1" target="_blank">/api/stocks/{id}</a></code>
                                </div>
                                <p>Get a specific stock item</p>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge method-post">POST</span>
                                    <code>/api/stocks</code>
                                </div>
                                <p>Create a new stock item</p>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge method-put">PUT</span>
                                    <code>/api/stocks/{id}</code>
                                </div>
                                <p>Update a stock item</p>

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge method-delete">DELETE</span>
                                    <code>/api/stocks/{id}</code>
                                </div>
                                <p class="mb-0">Delete a stock item</p>
                            </div>
                        </div>
                    </div>

                    <!-- Ingredients -->
                    <div class="col-md-6">
                        <div class="card endpoint-card">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0"><i class="fas fa-mortar-pestle me-2"></i>Ingredients</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge method-get">GET</span>
                                    <code><a href="/api/ingredients" target="_blank">/api/ingredients</a></code>
                                </div>
                                <p>Retrieve all ingredients</p>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge method-get">GET</span>
                                    <code><a href="/api/ingredients/1" target="_blank">/api/ingredients/{id}</a></code>
                                </div>
                                <p>Get a specific ingredient</p>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge method-post">POST</span>
                                    <code>/api/ingredients</code>
                                </div>
                                <p>Create a new ingredient</p>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge method-put">PUT</span>
                                    <code>/api/ingredients/{id}</code>
                                </div>
                                <p>Update an ingredient</p>

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge method-delete">DELETE</span>
                                    <code>/api/ingredients/{id}</code>
                                </div>
                                <p class="mb-0">Delete an ingredient</p>
                            </div>
                        </div>
                    </div>
                    <!-- Invoices -->
                    <div class="col-md-6">
                        <div class="card endpoint-card">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0"><i class="fas fa-mortar-pestle me-2"></i>Invoices</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge method-get">GET</span>
                                    <code><a href="/api/invoices" target="_blank">/api/invoices</a></code>
                                </div>
                                <p>Retrieve all invoices</p>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge method-get">GET</span>
                                    <code><a href="/api/invoices/1" target="_blank">/api/invoices/{id}</a></code>
                                </div>
                                <p>Get a specific invoice</p>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge method-post">POST</span>
                                    <code>/api/invoices</code>
                                </div>
                                <p>Create a new invoice</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                          
            </div>

            <div class="footer text-center">
                <div class="container">
                    <p>&copy; 2023 StockAI API. All rights reserved.</p>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
        HTML;

        return new Response($html);
    }

   
}