import { useEffect, useState } from "react";


// Type definition for receipt data structure
type ReceiptType = {
    id: number;
    date: string;
    total: number;
    supplier: string;
    imagePath: string;
};

export default function Receipts() {
    // Component state
    const [receipts, setReceipts] = useState<ReceiptType[]>([]);
    const [selectedImage, setSelectedImage] = useState<string | null>(null);

    /**
     * Fetches receipts data from API
     * Currently using mock data for demonstration
     */
    const fetchReceipts = () => {
        // Simulate API delay
        setTimeout(() => {
            const mockReceipts: ReceiptType[] = [
                {
                    id: 1,
                    date: "2024-06-20",
                    total: 123.45,
                    supplier: "Supplier A",
                    imagePath: "/receipts/20250920_175003_2025.09.18_25000565320250918666678-images-0.jpg.jpg",
                },
                {
                    id: 2,
                    date: "2024-06-18",
                    total: 67.89,
                    supplier: "Supplier B",
                    imagePath: "/receipts/20250920_175003_2025.09.18_25000565320250918666678-images-0.jpg.jpg",
                },
            ];
            
            setReceipts(mockReceipts);
        }, 500);

        // TODO: Replace mock data with actual API call
        // fetch("http://localhost:8001/api/receipts")
        //     .then((res) => res.json())
        //     .then((data) => setReceipts(data))
        //     .catch((err) => console.error("Failed to fetch receipts:", err));
    };

    // Load receipts when component mounts
    useEffect(() => {
        fetchReceipts();
    }, []);

    /**
     * Handles clicking on a receipt row to open the image modal
     */
    const handleReceiptClick = (imagePath: string) => {
        setSelectedImage(imagePath);
    };

    /**
     * Closes the image modal
     */
    const closeModal = () => {
        setSelectedImage(null);
    };

    /**
     * Prevents modal from closing when clicking inside the modal content
     */
    const handleModalContentClick = (e: React.MouseEvent) => {
        e.stopPropagation();
    };

    return (
        <main className="min-h-screen bg-gradient-to-br from-slate-50 to-indigo-100 flex flex-col font-sans">
            {/* Page Header */}
            <header className="text-center mt-12 mb-10">
                <h1 className="text-4xl md:text-5xl font-bold text-slate-800 tracking-tight drop-shadow">
                    Receipts <span className="text-indigo-600">History</span>
                </h1>
            </header>

            {/* Receipts Table Container */}
            <div className="overflow-x-auto mx-auto w-full max-w-4xl">
                <table className="min-w-full bg-white rounded-2xl shadow-xl border border-indigo-100">
                    {/* Table Header */}
                    <thead>
                        <tr>
                            <th className="py-3 px-4 text-left text-indigo-700 font-semibold">
                                Date
                            </th>
                            <th className="py-3 px-4 text-left text-indigo-700 font-semibold">
                                Supplier
                            </th>
                            <th className="py-3 px-4 text-left text-indigo-700 font-semibold">
                                Total
                            </th>
                        </tr>
                    </thead>

                    {/* Table Body */}
                    <tbody>
                        {/* Receipt Rows */}
                        {receipts.map((receipt) => (
                            <tr
                                key={receipt.id}
                                className="cursor-pointer hover:bg-indigo-50 transition border-b border-slate-100 last:border-b-0"
                                onClick={() => handleReceiptClick(receipt.imagePath)}
                            >
                                <td className="py-3 px-4 text-slate-700 font-medium">{receipt.date}</td>
                                <td className="py-3 px-4 text-indigo-800 font-semibold">{receipt.supplier}</td>
                                <td className="py-3 px-4 text-emerald-700 font-bold">${receipt.total.toFixed(2)}</td>
                            </tr>
                        ))}

                        {/* Empty State */}
                        {receipts.length === 0 && (
                            <tr>
                                <td colSpan={3} className="py-6 text-center text-slate-500 italic">
                                    No receipts found.
                                </td>
                            </tr>
                        )}
                    </tbody>
                </table>
            </div>

            {/* Receipt Image Modal */}
            {selectedImage && (
                <div
                    className="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50"
                    onClick={closeModal}
                >
                    <div
                        className="bg-white rounded-xl shadow-2xl p-4 max-w-lg w-full flex flex-col items-center"
                        onClick={handleModalContentClick}
                    >
                        {/* Receipt Image */}
                        <img
                            src={selectedImage}
                            alt="Receipt"
                            className="max-h-[70vh] w-auto rounded-lg mb-4"
                        />

                        {/* Close Button */}
                        <button
                            className="mt-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold transition-colors"
                            onClick={closeModal}
                        >
                            Close
                        </button>
                    </div>
                </div>
            )}
        </main>
    );
}