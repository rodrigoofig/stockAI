import { useRef, useState } from "react";

export function FileUpload() {
    const [selectedFile, setSelectedFile] = useState<File | null>(null);
    const [uploading, setUploading] = useState(false);
    const [message, setMessage] = useState<string | null>(null);
    const fileInputRef = useRef<HTMLInputElement>(null);

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files[0]) {
            setSelectedFile(e.target.files[0]);
            setMessage(null);
        }
    };

    const handleUpload = async () => {
        if (!selectedFile) {
            setMessage("Please select a file to upload.");
            return;
        }
        setUploading(true);
        setMessage(null);

        const formData = new FormData();
        formData.append("file", selectedFile);

        try {
            const res = await fetch("http://localhost:8000/read-image/", {
            method: "POST",
            body: formData,
            });
            if (res.ok) {
            setMessage("File uploaded and processed successfully!");
            setSelectedFile(null);
            if (fileInputRef.current) fileInputRef.current.value = "";
            } else {
            setMessage("Failed to upload or process file.");
            }
        } catch (err) {
            setMessage("An error occurred during upload.");
        } finally {
            setUploading(false);
        }
    };

    return (
        <main className="min-h-screen bg-gradient-to-br from-slate-50 to-indigo-100 flex flex-col font-sans">
            <h1 className="text-4xl md:text-5xl font-bold text-slate-800 mt-12 mb-10 tracking-tight drop-shadow self-center">
                Upload a <span className="text-indigo-600">File</span>
            </h1>
            <div className="flex flex-col items-center justify-center">
                <div className="shadow-2xl rounded-3xl bg-white p-8 w-full max-w-md border border-indigo-100">
                    <input
                        ref={fileInputRef}
                        type="file"
                        className="mb-4 block w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                        onChange={handleFileChange}
                        disabled={uploading}
                    />
                    <button
                        className="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors font-semibold disabled:opacity-50"
                        onClick={handleUpload}
                        disabled={uploading}
                    >
                        {uploading ? "Uploading..." : "Upload"}
                    </button>
                    {message && (
                        <div className="mt-4 text-center text-indigo-700">{message}</div>
                    )}
                </div>
            </div>
        </main>
    );
}