import type { Route } from "./+types/home";
import { Sales } from "../sales/sales";
import { FileUpload } from "../receipt/receipt";
import { useState } from "react";

export function meta({}: Route.MetaArgs) {
  return [
    { title: "New React Router App" },
    { name: "description", content: "Welcome to React Router!" },
  ];
}

export default function Home() {
  const [selectedPage, setSelectedPage] = useState("sales");

  return (
    <div className="flex h-screen">
      <nav className="w-52 bg-gray-100 p-4 border-r border-gray-300">
        <ul className="list-none p-0">
          <li>
            <button
              className={`w-full text-left px-4 py-2 rounded ${
                selectedPage === "sales"
                  ? "bg-gray-200 font-semibold"
                  : "bg-transparent"
              } hover:bg-gray-200 transition`}
              onClick={() => setSelectedPage("sales")}
            >
              Sales
            </button>
          </li>
          <li>
            <button
              className={`w-full text-left px-4 py-2 rounded ${
                selectedPage === "receipt"
                  ? "bg-gray-200 font-semibold"
                  : "bg-transparent"
              } hover:bg-gray-200 transition`}
              onClick={() => setSelectedPage("receipt")}
            >
              Receipt
            </button>
          </li>
        </ul>
      </nav>
      <main className="flex-1 p-8">
        {selectedPage === "sales" && <Sales />}
        {selectedPage === "receipt" && <FileUpload />}
      </main>
    </div>
  );
}
