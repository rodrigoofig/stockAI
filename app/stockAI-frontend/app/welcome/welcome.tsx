import Product from "../components/product";
import burgerImage from "../images/burger.jpg";
import water from "../images/water.avif";

export function Welcome() {
  return (
    <main className="min-h-screen bg-gradient-to-br from-slate-50 to-indigo-100 flex flex-col font-sans">
      <h1 className="text-4xl md:text-5xl font-bold text-slate-800 mt-12 mb-10 tracking-tight drop-shadow self-center">
        Welcome to <span className="text-indigo-600">StockAI!</span>
      </h1>

      <div className="flex flex-row gap-8 justify-center">
        <div className="shadow-xl rounded-2xl bg-white p-8">
          <Product image={burgerImage} name="Burger" price={29.99} />
        </div>
        <div className="shadow-xl rounded-2xl bg-white p-8">
          <Product image={water} name="Water" price={4.99} />
        </div>
        {/* Add more <Product /> components here for more products */}
      </div>
    </main>
  );
}
