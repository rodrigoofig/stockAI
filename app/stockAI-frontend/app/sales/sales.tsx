import { useEffect, useState } from "react";
import image from "../images/burger.jpg";
type ProductType = {
  id: number;
  name: string;
  price: number;
  linkImage?: string | null;
  description: string;
  hasIngredients: boolean;
  nearToFinish?: {
    ingredient: string;
    stockQuantity: number;
    maxSales: number;
  }[];
  ingredients?: {
    id: number;
    name: string;
    quantity: number;
    unit: string;
    stockQuantity: number;
    maxSales: number;
  }[];
};

export function Sales() {
  const [products, setProducts] = useState<ProductType[]>([]);

  const fetchProducts = () => {
    fetch("http://localhost:8001/api/products")
      .then((res) => res.json())
      .then((data) => setProducts(data))
      .catch((err) => console.error(err));
  };

  useEffect(() => {
    fetchProducts();
  }, []);
  
  return (
    <main className="min-h-screen bg-gradient-to-br from-slate-50 to-indigo-100 flex flex-col font-sans">
      <h1 className="text-4xl md:text-5xl font-bold text-slate-800 mt-12 mb-10 tracking-tight drop-shadow self-center">
        Welcome to <span className="text-indigo-600">StockAI!</span>
      </h1>

      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 justify-items-center">
        {products.map((product) => (
          <div
            key={product.id}
            className="shadow-2xl rounded-3xl bg-white p-6 w-full max-w-sm transition-transform hover:scale-105 hover:shadow-indigo-200 border border-indigo-100"
          >
            <img
              src={product.linkImage || image}
              alt={product.name}
              className="rounded-xl w-full h-48 object-cover mb-4"
            />
            <div className="flex flex-col gap-2">
              <h2 className="text-2xl font-semibold text-indigo-700">{product.name}</h2>
              <p className="text-slate-600 text-sm mb-2">{product.description}</p>
              <div className="flex items-center justify-between mt-2">
                <span className="text-lg font-bold text-indigo-600">${product.price.toFixed(2)}</span>
                {product.nearToFinish && product.nearToFinish.length > 0 ? (
                  product.nearToFinish.some((i) => i.stockQuantity > 0) ? (
                    <button
                      className="bg-yellow-400 text-yellow-900 text-xs px-3 py-1 rounded-full hover:bg-yellow-500 transition-colors font-semibold"
                      onClick={() =>
                        alert(
                          `Ingredients:\n${
                            product.nearToFinish
                              ?.filter((i) => i.stockQuantity > 0)
                              .map((i) => `${i.ingredient ?? product.name} (${i.stockQuantity})`)
                              .join("\n") || "No ingredients listed."
                          }`
                        )
                      }
                    >
                      {product.hasIngredients
                        ? "Some Ingredients Near to Finish"
                        : "Near to Finish"}
                    </button>
                  ) : (
                    <button
                      className="bg-red-500 text-white text-xs px-3 py-1 rounded-full font-semibold cursor-not-allowed"
                      disabled
                    >
                      Out of Stock
                    </button>
                  )
                ) : null}
              </div>
                <button
                  className="mt-4 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-semibold disabled:bg-gray-400 disabled:cursor-not-allowed"
                  disabled={
                  product.ingredients?.some((ingredient) => ingredient.maxSales < 1) ||
                  product.nearToFinish?.some((item) => item.maxSales < 1)
                  }
                  onClick={async () => {
                  try {
                    await fetch("http://localhost:8001/api/orders", {
                      method: "POST",
                      headers: { "Content-Type": "application/json" },
                      body: JSON.stringify({
                        totalPrice: product.price,
                        items: [{ product_id: product.id, quantity: 1 }],
                      }),
                    });
                    alert(`Order placed for ${product.name}`);
                    fetchProducts(); // Refresh products after order
                  } catch (err) {
                    alert("Failed to place order.");
                  }
                  }}
                >
                  Buy
                </button>
            </div>
          </div>
        ))}
      </div>
    </main>
  );
}