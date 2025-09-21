import { useEffect, useState } from "react";

// Type definition for message data structure
type MessageType = {
    id: number;
    title: string;
    supplierName: string;
    supplierId: number;
    html: string;
    recipient: string;
    createdAt: string;
};

export default function Messages() {
    const [messages, setMessages] = useState<MessageType[]>([]);
    const [selectedMessage, setSelectedMessage] = useState<MessageType | null>(null);

    const fetchMessages = async () => {
        try {
            const res = await fetch("http://localhost:8001/api/messages");
            if (!res.ok) throw new Error("Failed to fetch messages");

            const data = await res.json();

            const mappedMessages: MessageType[] = data.map((item: any) => {
                // format createdAt into YYYY-MM-DD for table
                const formattedDate = new Date(item.createdAt).toLocaleDateString("en-GB", {
                    year: "numeric",
                    month: "2-digit",
                    day: "2-digit",
                });

                return {
                    ...item,
                    createdAt: formattedDate,
                };
            });

            setMessages(mappedMessages);
        } catch (err) {
            console.error("Failed to fetch messages:", err);
        }
    };

    useEffect(() => {
        fetchMessages();
    }, []);

    const handleRowClick = (message: MessageType) => setSelectedMessage(message);
    const closeModal = () => setSelectedMessage(null);
    const handleModalContentClick = (e: React.MouseEvent) => e.stopPropagation();

    const handleSupplierClick = (
        e: React.MouseEvent,
        supplierId: number
    ) => {
        e.stopPropagation();
        window.open(`http://localhost:3006/supplier/${supplierId}`, "_blank");
    };

    return (
        <main className="min-h-screen bg-gradient-to-br from-slate-50 to-indigo-100 flex flex-col font-sans">
            {/* Page Header */}
            <header className="text-center mt-12 mb-10">
                <h1 className="text-4xl md:text-5xl font-bold text-slate-800 tracking-tight drop-shadow">
                    Messages <span className="text-indigo-600">History</span>
                </h1>
            </header>

            {/* Messages Table */}
            <div className="overflow-x-auto mx-auto w-full max-w-4xl">
                <table className="min-w-full bg-white rounded-2xl shadow-xl border border-indigo-100">
                    <thead>
                        <tr>
                            <th className="py-3 px-4 text-left text-indigo-700 font-semibold">Date</th>
                            <th className="py-3 px-4 text-left text-indigo-700 font-semibold">Title</th>
                            <th className="py-3 px-4 text-left text-indigo-700 font-semibold">Supplier</th>
                            <th className="py-3 px-4 text-left text-indigo-700 font-semibold">Recipient</th>
                        </tr>
                    </thead>
                    <tbody>
                        {messages.map((msg) => (
                            <tr
                                key={msg.id}
                                className="cursor-pointer hover:bg-indigo-50 transition border-b border-slate-100 last:border-b-0"
                                onClick={() => handleRowClick(msg)}
                            >
                                <td className="py-3 px-4 text-slate-700 font-medium">{msg.createdAt}</td>
                                <td className="py-3 px-4 text-indigo-800 font-semibold">{msg.title}</td>
                                <td className="py-3 px-4">
                                    <button
                                        className="text-indigo-600 underline hover:text-indigo-800 font-semibold"
                                        onClick={e => handleSupplierClick(e, msg.supplierId)}
                                        tabIndex={0}
                                    >
                                        {msg.supplierName.length > 10
                                            ? msg.supplierName.slice(0, 10) + "..."
                                            : msg.supplierName}
                                    </button>
                                </td>
                                <td className="py-3 px-4 text-slate-700">{msg.recipient}</td>
                            </tr>
                        ))}
                        {messages.length === 0 && (
                            <tr>
                                <td colSpan={4} className="py-6 text-center text-slate-500 italic">
                                    No messages found.
                                </td>
                            </tr>
                        )}
                    </tbody>
                </table>
            </div>

            {/* Message Modal */}
            {selectedMessage && (
                <div
                    className="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50"
                    onClick={closeModal}
                >
                    <div
                        className="bg-white rounded-xl shadow-2xl p-4 max-w-lg w-full flex flex-col items-center"
                        onClick={handleModalContentClick}
                    >
                        <h2 className="text-xl font-bold mb-2 text-indigo-700">{selectedMessage.title}</h2>
                        <div className="mb-2 text-slate-700 text-sm">
                            <span className="font-semibold">Supplier: </span>
                            <button
                                className="text-indigo-600 underline hover:text-indigo-800"
                                onClick={e => handleSupplierClick(e, selectedMessage.supplierId)}
                            >
                                {selectedMessage.supplierName}
                            </button>
                        </div>
                        <div className="mb-2 text-slate-700 text-sm">
                            <span className="font-semibold">Recipient: </span>
                            {selectedMessage.recipient}
                        </div>
                        <div className="mb-4 text-slate-500 text-xs">
                            {selectedMessage.createdAt}
                        </div>
                        <div
                            className="w-full mb-4 prose max-w-none text-center text-black"
                            dangerouslySetInnerHTML={{ __html: selectedMessage.html }}
                        />
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