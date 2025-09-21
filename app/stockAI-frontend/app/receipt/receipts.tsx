import { useEffect, useState } from "react";

// Type definition for receipt data structure
type ReceiptType = {
    id: number;
    date: string;
    supplier: string;
    imagePath: string;
};

export default function Receipts() {
    const [receipts, setReceipts] = useState<ReceiptType[]>([]);
    const [selectedImage, setSelectedImage] = useState<string | null>(null);

    const fetchReceipts = async () => {
        try {
            const res = await fetch("http://localhost:8001/api/invoices");
            if (!res.ok) throw new Error("Failed to fetch receipts");

            const data = await res.json();

            const mappedReceipts: ReceiptType[] = data.map((item: any) => {
                // format createdAt into YYYY-MM-DD for table
                const formattedDate = new Date(item.createdAt).toLocaleDateString("en-GB", {
                    year: "numeric",
                    month: "2-digit",
                    day: "2-digit",
                });

                return {
                    id: item.id,
                    date: formattedDate,
                    supplier: item.supplierName,
                    imagePath: `data:image/jpeg;base64,${item.linkImageInvoice}`, // ✅ usable in <img src=...>
                };
            });

            setReceipts(mappedReceipts);
        } catch (err) {
            console.error("Failed to fetch receipts:", err);
        }
    };

    useEffect(() => {
        fetchReceipts();
    }, []);

    const handleReceiptClick = (imagePath: string) => setSelectedImage(imagePath);
    const closeModal = () => setSelectedImage(null);
    const handleModalContentClick = (e: React.MouseEvent) => e.stopPropagation();

    return (
        <main className="min-h-screen bg-gradient-to-br from-slate-50 to-indigo-100 flex flex-col font-sans">
            {/* Page Header */}
            <header className="text-center mt-12 mb-10">
                <h1 className="text-4xl md:text-5xl font-bold text-slate-800 tracking-tight drop-shadow">
                    Receipts <span className="text-indigo-600">History</span>
                </h1>
            </header>

            {/* Receipts Table */}
            <div className="overflow-x-auto mx-auto w-full max-w-4xl">
                <table className="min-w-full bg-white rounded-2xl shadow-xl border border-indigo-100">
                    <thead>
                        <tr>
                            <th className="py-3 px-4 text-left text-indigo-700 font-semibold">Date</th>
                            <th className="py-3 px-4 text-left text-indigo-700 font-semibold">Supplier</th>
                        </tr>
                    </thead>
                    <tbody>
                        {receipts.map((receipt) => (
                            <tr
                                key={receipt.id}
                                className="cursor-pointer hover:bg-indigo-50 transition border-b border-slate-100 last:border-b-0"
                                onClick={() => handleReceiptClick(receipt.imagePath)}
                            >
                                <td className="py-3 px-4 text-slate-700 font-medium">{receipt.date}</td>
                                <td className="py-3 px-4 text-indigo-800 font-semibold">{receipt.supplier}</td>
                            </tr>
                        ))}
                        {receipts.length === 0 && (
                            <tr>
                                <td colSpan={2} className="py-6 text-center text-slate-500 italic">
                                    No receipts found.
                                </td>
                            </tr>
                        )}
                    </tbody>
                </table>
            </div>

            {/* Image Modal */}
            {selectedImage && (
                <div
                    className="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50"
                    onClick={closeModal}
                >
                    <div
                        className="bg-white rounded-xl shadow-2xl p-4 max-w-lg w-full flex flex-col items-center"
                        onClick={handleModalContentClick}
                    >
                        <img src={selectedImage} alt="Receipt" className="max-h-[70vh] w-auto rounded-lg mb-4" />
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
