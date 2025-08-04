import { AppShell } from '@/components/app-shell';
import { Head } from '@inertiajs/react';

interface DashboardStats {
    todayVisits: number;
    todayRevenue: number;
    todayPatients: number;
    lowStockCount: number;
}

interface LowStockItem {
    id: number;
    name: string;
    stock: number;
    minimal_alert: number;
    unit: string;
    type: string;
}

interface TopSellingItem {
    id: number;
    name: string;
    total_sold: number;
    unit: string;
    type: string;
}

interface RecentVisit {
    id: number;
    visit_date: string;
    status: string;
    patient: {
        id: number;
        name: string;
        patient_id: string;
    };
    bill?: {
        id: number;
        status: string;
        total: number;
    };
}

interface Props {
    stats: DashboardStats;
    lowStockItems: LowStockItem[];
    topSellingItems: TopSellingItem[];
    recentVisits: RecentVisit[];
    [key: string]: unknown;
}

export default function Dashboard({ stats, lowStockItems, topSellingItems, recentVisits }: Props) {
    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(amount);
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        });
    };

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'selesai':
            case 'paid':
                return 'bg-green-100 text-green-800';
            case 'menunggu':
            case 'pending':
                return 'bg-yellow-100 text-yellow-800';
            case 'dibatalkan':
            case 'cancelled':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    };

    const getTypeEmoji = (type: string) => {
        switch (type) {
            case 'obat':
                return '💊';
            case 'barang_lain':
                return '🧴';
            case 'alat_medis':
                return '🩺';
            default:
                return '📦';
        }
    };

    return (
        <>
            <Head title="Dashboard Klinik" />
            
            <AppShell>
                <div className="space-y-6">
                    {/* Page Header */}
                    <div className="border-b border-gray-200 pb-4">
                        <h1 className="text-3xl font-bold text-gray-900">🏥 Dashboard Klinik</h1>
                        <p className="mt-2 text-gray-600">
                            Ringkasan aktivitas klinik hari ini - {new Date().toLocaleDateString('id-ID', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            })}
                        </p>
                    </div>

                    {/* Stats Cards */}
                    <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        <div className="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-200">
                            <div className="px-6 py-5">
                                <div className="flex items-center">
                                    <div className="flex-shrink-0">
                                        <div className="text-3xl">👥</div>
                                    </div>
                                    <div className="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt className="text-sm font-medium text-gray-500 truncate">
                                                Kunjungan Hari Ini
                                            </dt>
                                            <dd className="text-2xl font-bold text-gray-900">
                                                {stats.todayVisits}
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-200">
                            <div className="px-6 py-5">
                                <div className="flex items-center">
                                    <div className="flex-shrink-0">
                                        <div className="text-3xl">👤</div>
                                    </div>
                                    <div className="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt className="text-sm font-medium text-gray-500 truncate">
                                                Pasien Hari Ini
                                            </dt>
                                            <dd className="text-2xl font-bold text-gray-900">
                                                {stats.todayPatients}
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-200">
                            <div className="px-6 py-5">
                                <div className="flex items-center">
                                    <div className="flex-shrink-0">
                                        <div className="text-3xl">💰</div>
                                    </div>
                                    <div className="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt className="text-sm font-medium text-gray-500 truncate">
                                                Pendapatan Hari Ini
                                            </dt>
                                            <dd className="text-2xl font-bold text-green-600">
                                                {formatCurrency(stats.todayRevenue)}
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-200">
                            <div className="px-6 py-5">
                                <div className="flex items-center">
                                    <div className="flex-shrink-0">
                                        <div className="text-3xl">⚠️</div>
                                    </div>
                                    <div className="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt className="text-sm font-medium text-gray-500 truncate">
                                                Stok Rendah
                                            </dt>
                                            <dd className="text-2xl font-bold text-red-600">
                                                {stats.lowStockCount}
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="grid gap-6 lg:grid-cols-2">
                        {/* Low Stock Items */}
                        <div className="bg-white shadow-lg rounded-xl border border-gray-200">
                            <div className="px-6 py-4 border-b border-gray-200">
                                <h2 className="text-lg font-semibold text-gray-900">⚠️ Item Stok Rendah</h2>
                            </div>
                            <div className="divide-y divide-gray-200">
                                {lowStockItems.length > 0 ? (
                                    lowStockItems.slice(0, 5).map((item) => (
                                        <div key={item.id} className="px-6 py-4">
                                            <div className="flex items-center justify-between">
                                                <div className="flex items-center space-x-3">
                                                    <span className="text-xl">{getTypeEmoji(item.type)}</span>
                                                    <div>
                                                        <p className="text-sm font-medium text-gray-900">{item.name}</p>
                                                        <p className="text-xs text-gray-500">
                                                            Minimal: {item.minimal_alert} {item.unit}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div className="text-right">
                                                    <p className={`text-sm font-semibold ${
                                                        item.stock === 0 ? 'text-red-600' : 'text-orange-600'
                                                    }`}>
                                                        {item.stock} {item.unit}
                                                    </p>
                                                    {item.stock === 0 && (
                                                        <p className="text-xs text-red-500">Stok Habis!</p>
                                                    )}
                                                </div>
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <div className="px-6 py-8 text-center text-gray-500">
                                        <div className="text-4xl mb-2">✅</div>
                                        <p>Semua item stok aman!</p>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Top Selling Items */}
                        <div className="bg-white shadow-lg rounded-xl border border-gray-200">
                            <div className="px-6 py-4 border-b border-gray-200">
                                <h2 className="text-lg font-semibold text-gray-900">🏆 Item Terlaris</h2>
                            </div>
                            <div className="divide-y divide-gray-200">
                                {topSellingItems.length > 0 ? (
                                    topSellingItems.slice(0, 5).map((item, index) => (
                                        <div key={item.id} className="px-6 py-4">
                                            <div className="flex items-center justify-between">
                                                <div className="flex items-center space-x-3">
                                                    <div className="flex items-center justify-center w-6 h-6 rounded-full bg-yellow-100 text-yellow-800 text-xs font-bold">
                                                        {index + 1}
                                                    </div>
                                                    <span className="text-xl">{getTypeEmoji(item.type)}</span>
                                                    <div>
                                                        <p className="text-sm font-medium text-gray-900">{item.name}</p>
                                                        <p className="text-xs text-gray-500 capitalize">
                                                            {item.type.replace('_', ' ')}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div className="text-right">
                                                    <p className="text-sm font-semibold text-blue-600">
                                                        {item.total_sold} {item.unit}
                                                    </p>
                                                    <p className="text-xs text-gray-500">Terjual</p>
                                                </div>
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <div className="px-6 py-8 text-center text-gray-500">
                                        <div className="text-4xl mb-2">📊</div>
                                        <p>Belum ada data penjualan</p>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Recent Visits */}
                    <div className="bg-white shadow-lg rounded-xl border border-gray-200">
                        <div className="px-6 py-4 border-b border-gray-200">
                            <h2 className="text-lg font-semibold text-gray-900">🕒 Kunjungan Terakhir</h2>
                        </div>
                        <div className="overflow-x-auto">
                            {recentVisits.length > 0 ? (
                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Pasien
                                            </th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                ID Pasien
                                            </th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Waktu Kunjungan
                                            </th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status Kunjungan
                                            </th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Pembayaran
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {recentVisits.slice(0, 8).map((visit) => (
                                            <tr key={visit.id} className="hover:bg-gray-50">
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {visit.patient.name}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {visit.patient.patient_id}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {formatDate(visit.visit_date)}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className={`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getStatusColor(visit.status)}`}>
                                                        {visit.status === 'selesai' ? 'Selesai' : 
                                                         visit.status === 'menunggu' ? 'Menunggu' : 'Dibatalkan'}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    {visit.bill ? (
                                                        <div className="flex flex-col">
                                                            <span className={`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getStatusColor(visit.bill.status)}`}>
                                                                {visit.bill.status === 'paid' ? 'Lunas' :
                                                                 visit.bill.status === 'pending' ? 'Belum Bayar' : 'Dibatalkan'}
                                                            </span>
                                                            {visit.bill.status === 'paid' && (
                                                                <span className="text-xs text-gray-500 mt-1">
                                                                    {formatCurrency(visit.bill.total)}
                                                                </span>
                                                            )}
                                                        </div>
                                                    ) : (
                                                        <span className="text-xs text-gray-400">Belum ada tagihan</span>
                                                    )}
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            ) : (
                                <div className="px-6 py-8 text-center text-gray-500">
                                    <div className="text-4xl mb-2">📋</div>
                                    <p>Belum ada kunjungan</p>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </AppShell>
        </>
    );
}