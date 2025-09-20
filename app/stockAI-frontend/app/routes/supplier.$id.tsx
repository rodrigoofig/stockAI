import type { Route } from "./+types/supplier.$id";
import { useEffect, useState } from "react";

type Supplier = {
    id: number;
    name: string;
    fone: string;
    cel: string;
    email: string;
    address: string;
    nif: string;
    urlApi: string;
    token: string;
    requestType: string;
};


export async function loader({ params }: { params: { id: string } }) {
    const res = await fetch(`http://localhost:8001/api/suppliers/${params.id}`);
    if (!res.ok) {
        throw new Error("Failed to fetch supplier");
    }
    const supplier = await res.json();
    return supplier;
}

export default function Supplier({ loaderData }: Route.ComponentProps) {
    const supplier = loaderData;

    return (
        <main className="min-h-screen bg-gradient-to-br from-slate-50 to-indigo-100 flex flex-col font-sans">
            <h1 className="text-4xl md:text-5xl font-bold text-slate-800 mt-12 mb-10 tracking-tight drop-shadow self-center">
                Supplier <span className="text-indigo-600">Details</span>
            </h1>
            <div className="flex justify-center">
                <div className="shadow-2xl rounded-3xl bg-white p-8 w-full max-w-lg transition-transform hover:scale-105 hover:shadow-indigo-200 border border-indigo-100">
                    <h2 className="text-2xl font-semibold text-indigo-700 mb-4">
                        {supplier.name}
                    </h2>
                    <div className="space-y-3 text-slate-700">
                        <div>
                            <span className="font-semibold">ID:</span> {supplier.id}
                        </div>
                        <div>
                            <span className="font-semibold">Phone:</span> {supplier.fone}
                        </div>
                        <div>
                            <span className="font-semibold">Cell:</span> {supplier.cel}
                        </div>
                        <div>
                            <span className="font-semibold">Email:</span> {supplier.email}
                        </div>
                        <div>
                            <span className="font-semibold">Address:</span> {supplier.address}
                        </div>
                        <div>
                            <span className="font-semibold">NIF:</span> {supplier.nif}
                        </div>
                        <div>
                            <span className="font-semibold">API URL:</span>{" "}
                            <a
                                href={supplier.urlApi}
                                className="text-indigo-600 underline break-all"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                {supplier.urlApi}
                            </a>
                        </div>
                        <div>
                            <span className="font-semibold">Token:</span>{" "}
                            <span className="bg-slate-100 px-2 py-1 rounded text-xs break-all">
                                {supplier.token}
                            </span>
                        </div>
                        <div>
                            <span className="font-semibold">Request Type:</span> {supplier.requestType}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    );
}
