import { useEffect, useState } from "react";

type StockType = {
    id: number;
    name: string;
    quantity: number;
    unit: string;
    supplier_id: number;
    product_id: number | null;
};

export function Stock() {
    const [stocks, setStocks] = useState<StockType[]>([]);

    const fetchStocks = () => {
        fetch("http://localhost:8001/api/stocks")
            .then((res) => res.json())
            .then((data) => setStocks(data))
            .catch((err) => console.error(err));
    };

    useEffect(() => {
        fetchStocks();
    }, []);

    // Sort stocks: out of stock (quantity === 0) first, then others
    const sortedStocks = [...stocks].sort((a, b) => {
        if (a.quantity === 0 && b.quantity !== 0) return -1;
        if (a.quantity !== 0 && b.quantity === 0) return 1;
        return 0;
    });

    return (
        <main className="min-h-screen bg-gradient-to-br from-slate-50 to-indigo-100 flex flex-col font-sans">
            <h1 className="text-4xl md:text-5xl font-bold text-slate-800 mt-12 mb-10 tracking-tight drop-shadow self-center">
                Current <span className="text-indigo-600">Stock</span>
            </h1>

            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 justify-items-center">
                {sortedStocks.map((stock) => (
                    <div
                        key={stock.id}
                        className="shadow-2xl rounded-3xl bg-white p-6 w-full max-w-sm transition-transform hover:scale-105 hover:shadow-indigo-200 border border-indigo-100"
                    >
                        <div className="flex flex-col gap-3">
                            <h2 className="text-2xl font-semibold text-indigo-700">
                                {stock.name}
                            </h2>

                            <p className="text-slate-600 text-sm">
                                Supplier ID: <span className="font-medium">{stock.supplier_id}</span>
                            </p>

                            <div className="flex items-center justify-between mt-2">
                                <span
                                    className={`text-lg font-bold ${
                                        stock.quantity > 0 ? "text-indigo-600" : "text-red-500"
                                    }`}
                                >
                                    {stock.quantity} {stock.unit}
                                </span>

                                {stock.quantity > 0 ? (
                                    <button
                                        className="bg-green-500 text-white text-xs px-3 py-1 rounded-full hover:bg-green-600 transition-colors font-semibold"
                                        onClick={() =>
                                            alert(`${stock.name} is in stock: ${stock.quantity} ${stock.unit}`)
                                        }
                                    >
                                        Available
                                    </button>
                                ) : (
                                    <button
                                        className="bg-red-500 text-white text-xs px-3 py-1 rounded-full font-semibold cursor-not-allowed"
                                        disabled
                                    >
                                        Out of Stock
                                    </button>
                                )}
                            </div>
                        </div>
                    </div>
                ))}
            </div>
        </main>
    );
}
