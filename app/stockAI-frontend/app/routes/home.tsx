import type { Route } from "./+types/home";
import { Sales } from "../sales/sales";
import { FileUpload } from "../receipt/receipt";
import { Stock } from "../stock/stock"; // Import Stock component
import { useState } from "react";
import { ChartBar, Upload, Menu, X, Boxes } from "lucide-react"; // Import Boxes icon

export function meta({}: Route.MetaArgs) {
  return [
    { title: "StockAI Dashboard" },
    { name: "description", content: "Manage sales and receipts with ease." },
  ];
}

const NAV_ITEMS = [
  { key: "sales", label: "Sales", icon: <ChartBar className="w-5 h-5 mr-3" /> },
  { key: "receipt", label: "Receipt", icon: <Upload className="w-5 h-5 mr-3" /> },
  { key: "stock", label: "Stock", icon: <Boxes className="w-5 h-5 mr-3" /> }, // Add Stock menu item
];

export default function Home() {
  const [selectedPage, setSelectedPage] = useState("sales");
  const [menuOpen, setMenuOpen] = useState(false);

  return (
    <div className="flex h-screen bg-gray-50">
      {/* Sidebar (desktop) */}
      <aside className="hidden lg:flex flex-col w-64 bg-white border-r shadow-sm">
        <div className="h-16 flex items-center justify-center border-b bg-gradient-to-r from-indigo-600 to-indigo-700">
          <span className="text-xl font-bold text-white tracking-wide">StockAI</span>
        </div>
        <nav className="flex-1 p-6 space-y-3">
          {NAV_ITEMS.map((item) => (
            <button
              key={item.key}
              onClick={() => setSelectedPage(item.key)}
              className={`flex items-center w-full px-4 py-3 rounded-xl transition-all duration-200 text-left
                ${
                  selectedPage === item.key
                    ? "bg-indigo-50 text-indigo-700 font-semibold shadow-sm border border-indigo-100"
                    : "text-gray-600 hover:bg-gray-50 hover:text-gray-900"
                }`}
            >
              <span className={selectedPage === item.key ? "text-indigo-600" : "text-gray-400"}>
                {item.icon}
              </span>
              {item.label}
            </button>
          ))}
        </nav>
        <div className="p-6 border-t bg-gray-50">
          <p className="text-xs text-gray-500 text-center">Dashboard v1.0</p>
        </div>
      </aside>

      {/* Mobile Nav */}
      <nav className="lg:hidden fixed top-0 left-0 right-0 z-20 flex items-center justify-between bg-white border-b px-6 h-16 shadow-sm backdrop-blur-sm bg-white/95">
        <span className="text-xl font-bold bg-gradient-to-r from-indigo-600 to-indigo-700 bg-clip-text text-transparent">
          StockAI
        </span>
        <button 
          onClick={() => setMenuOpen((v) => !v)} 
          className="p-2 rounded-lg hover:bg-gray-100 transition-colors"
        >
          {menuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
        </button>
      </nav>

      {/* Mobile Dropdown */}
      {menuOpen && (
        <>
          <div 
            className="lg:hidden fixed inset-0 z-10 bg-black/20 backdrop-blur-sm"
            onClick={() => setMenuOpen(false)}
          />
          <div className="lg:hidden fixed top-16 left-0 right-0 z-20 bg-white border-b shadow-lg">
            <nav className="p-4 space-y-2 max-h-96 overflow-y-auto">
              {NAV_ITEMS.map((item) => (
                <button
                  key={item.key}
                  onClick={() => {
                    setSelectedPage(item.key);
                    setMenuOpen(false);
                  }}
                  className={`flex items-center w-full px-4 py-3 rounded-xl transition-all duration-200
                    ${
                      selectedPage === item.key
                        ? "bg-indigo-50 text-indigo-700 font-semibold shadow-sm border border-indigo-100"
                        : "text-gray-600 hover:bg-gray-50 hover:text-gray-900"
                    }`}
                >
                  <span className={selectedPage === item.key ? "text-indigo-600" : "text-gray-400"}>
                    {item.icon}
                  </span>
                  {item.label}
                </button>
              ))}
            </nav>
          </div>
        </>
      )}

      {/* Main Content */}
      <main className="flex-1 flex flex-col min-h-screen">
        <div className="flex-1 overflow-y-auto pt-20 lg:pt-0 px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
          <div className="max-w-7xl mx-auto h-full">
            <div className="bg-white rounded-2xl shadow-sm border border-gray-200 h-full min-h-[calc(100vh-8rem)] lg:min-h-[calc(100vh-4rem)]">
              <div className="px-6 lg:px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div className="flex items-center">
                  <span className="text-indigo-600 mr-3">
                    {NAV_ITEMS.find(item => item.key === selectedPage)?.icon}
                  </span>
                  <h1 className="text-2xl font-bold text-gray-900">
                    {NAV_ITEMS.find(item => item.key === selectedPage)?.label}
                  </h1>
                </div>
              </div>
              <div className="flex-1 p-6 lg:p-8">
                {selectedPage === "sales" && (
                  <div className="h-full">
                    <Sales />
                  </div>
                )}
                {selectedPage === "receipt" && (
                  <div className="h-full">
                    <FileUpload />
                  </div>
                )}
                {selectedPage === "stock" && (
                  <div className="h-full">
                    <Stock />
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  );
}