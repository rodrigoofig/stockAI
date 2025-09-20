import { useEffect, useState } from "react";
import image from "../images/burger.jpg";

type ProductType = {
  id: number;
  name: string;
  price: number;
  image?: string;
  hasIngredients: boolean;
  description: string;
  supplier_id: number | null;
  linkImage?: string | null;
};

export function Sales() {
  const [products, setProducts] = useState<ProductType[]>([]);

  useEffect(() => {
    fetch("http://localhost:8001/api/products")
      .then((res) => res.json())
      .then((data) => setProducts(data))
      .catch((err) => console.error(err));
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
                {product.hasIngredients && (
                  <span className="bg-indigo-100 text-indigo-700 text-xs px-3 py-1 rounded-full">
                    Has Ingredients
                  </span>
                )}
              </div>
              <button
                className="mt-4 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-semibold"
                onClick={() => alert(`Buying ${product.name}`)}
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