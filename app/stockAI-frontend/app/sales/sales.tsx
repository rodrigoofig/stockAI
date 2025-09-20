import { useEffect, useState } from "react";
import Product from "../components/product";
import image from "../images/burger.jpg"

type ProductType = {
  id: number;
  name: string;
  price: number;
  image?: string; // URL or path to image, optional since API doesn't provide it
  hasIngredients: boolean;
  description: string;
  supplier_id: number | null;
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

      <div className="flex flex-row gap-8 justify-center">
        {products.map((product) => (
          <div key={product.id} className="shadow-xl rounded-2xl bg-white p-8">
            <Product
              image={image}
              name={product.name}
              price={product.price}
            />
          </div>
        ))}
      </div>
    </main>
  );
}
