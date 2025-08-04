import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    return (
        <>
            <Head title="Sistem Manajemen Klinik">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="flex min-h-screen flex-col items-center bg-gradient-to-br from-blue-50 to-indigo-100 p-6 text-gray-900 lg:justify-center lg:p-8">
                <header className="mb-6 w-full max-w-4xl">
                    <nav className="flex items-center justify-end gap-4">
                        {auth.user ? (
                            <Link
                                href={route('dashboard')}
                                className="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-3 text-sm font-medium text-white shadow-lg hover:bg-blue-700 transition-colors"
                            >
                                🏥 Dashboard Klinik
                            </Link>
                        ) : (
                            <>
                                <Link
                                    href={route('login')}
                                    className="inline-block rounded-lg border border-blue-300 px-6 py-3 text-sm font-medium text-blue-700 hover:bg-blue-50 transition-colors"
                                >
                                    Masuk
                                </Link>
                                <Link
                                    href={route('register')}
                                    className="inline-block rounded-lg bg-blue-600 px-6 py-3 text-sm font-medium text-white hover:bg-blue-700 transition-colors"
                                >
                                    Daftar
                                </Link>
                            </>
                        )}
                    </nav>
                </header>

                <div className="flex w-full items-center justify-center">
                    <main className="w-full max-w-6xl">
                        <div className="bg-white rounded-3xl shadow-2xl overflow-hidden">
                            {/* Hero Section */}
                            <div className="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-16 text-center text-white">
                                <div className="mx-auto max-w-3xl">
                                    <h1 className="mb-6 text-5xl font-bold leading-tight">
                                        🏥 Sistem Manajemen Klinik
                                    </h1>
                                    <p className="mb-8 text-xl leading-relaxed opacity-90">
                                        Solusi lengkap untuk mengelola klinik Anda dengan efisien. 
                                        Kelola pasien, rekam medis, inventori, dan pembayaran dalam satu platform terintegrasi.
                                    </p>
                                    {auth.user ? (
                                        <Link
                                            href={route('dashboard')}
                                            className="inline-flex items-center gap-3 rounded-xl bg-white px-8 py-4 text-lg font-semibold text-blue-600 shadow-lg hover:bg-gray-50 transition-all transform hover:scale-105"
                                        >
                                            🚀 Buka Dashboard
                                        </Link>
                                    ) : (
                                        <div className="flex gap-4 justify-center">
                                            <Link
                                                href={route('login')}
                                                className="inline-flex items-center gap-2 rounded-xl bg-white px-8 py-4 text-lg font-semibold text-blue-600 shadow-lg hover:bg-gray-50 transition-all transform hover:scale-105"
                                            >
                                                👨‍⚕️ Masuk Sebagai Dokter
                                            </Link>
                                            <Link
                                                href={route('register')}
                                                className="inline-flex items-center gap-2 rounded-xl border-2 border-white px-8 py-4 text-lg font-semibold text-white hover:bg-white hover:text-blue-600 transition-all transform hover:scale-105"
                                            >
                                                📝 Daftar Sekarang
                                            </Link>
                                        </div>
                                    )}
                                </div>
                            </div>

                            {/* Features Section */}
                            <div className="px-8 py-16">
                                <div className="mx-auto max-w-5xl">
                                    <h2 className="mb-12 text-center text-3xl font-bold text-gray-900">
                                        ✨ Fitur Lengkap untuk Klinik Modern
                                    </h2>
                                    
                                    <div className="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                                        {/* Patient Management */}
                                        <div className="rounded-2xl bg-blue-50 p-6 text-center hover:shadow-lg transition-shadow">
                                            <div className="mb-4 text-4xl">👥</div>
                                            <h3 className="mb-3 text-xl font-semibold text-blue-900">Manajemen Pasien</h3>
                                            <p className="text-blue-700">
                                                Kelola data pasien lengkap dengan riwayat kesehatan, alergi, dan catatan medis.
                                            </p>
                                        </div>

                                        {/* Visit Management */}
                                        <div className="rounded-2xl bg-green-50 p-6 text-center hover:shadow-lg transition-shadow">
                                            <div className="mb-4 text-4xl">📋</div>
                                            <h3 className="mb-3 text-xl font-semibold text-green-900">Pencatatan Kunjungan</h3>
                                            <p className="text-green-700">
                                                Catat setiap kunjungan pasien dengan diagnosis, tindakan, dan rencana follow-up.
                                            </p>
                                        </div>

                                        {/* Medical Records */}
                                        <div className="rounded-2xl bg-purple-50 p-6 text-center hover:shadow-lg transition-shadow">
                                            <div className="mb-4 text-4xl">📝</div>
                                            <h3 className="mb-3 text-xl font-semibold text-purple-900">Rekam Medis Digital</h3>
                                            <p className="text-purple-700">
                                                Simpan rekam medis secara digital dengan akses cepat dan riwayat lengkap.
                                            </p>
                                        </div>

                                        {/* Prescription Management */}
                                        <div className="rounded-2xl bg-red-50 p-6 text-center hover:shadow-lg transition-shadow">
                                            <div className="mb-4 text-4xl">💊</div>
                                            <h3 className="mb-3 text-xl font-semibold text-red-900">Manajemen Resep</h3>
                                            <p className="text-red-700">
                                                Buat resep digital dengan peringatan alergi otomatis dan kontrol stok obat.
                                            </p>
                                        </div>

                                        {/* Inventory Management */}
                                        <div className="rounded-2xl bg-orange-50 p-6 text-center hover:shadow-lg transition-shadow">
                                            <div className="mb-4 text-4xl">📦</div>
                                            <h3 className="mb-3 text-xl font-semibold text-orange-900">Inventori Cerdas</h3>
                                            <p className="text-orange-700">
                                                Kelola stok obat dan alat medis dengan alert otomatis untuk stok menipis.
                                            </p>
                                        </div>

                                        {/* Payment System */}
                                        <div className="rounded-2xl bg-teal-50 p-6 text-center hover:shadow-lg transition-shadow">
                                            <div className="mb-4 text-4xl">💰</div>
                                            <h3 className="mb-3 text-xl font-semibold text-teal-900">Sistem Pembayaran</h3>
                                            <p className="text-teal-700">
                                                Proses pembayaran tunai dengan perhitungan kembalian otomatis dan cetak struk.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Stats Preview */}
                            <div className="bg-gray-50 px-8 py-12">
                                <div className="mx-auto max-w-4xl text-center">
                                    <h2 className="mb-8 text-2xl font-bold text-gray-900">
                                        📊 Dashboard yang Informatif
                                    </h2>
                                    <div className="grid gap-6 md:grid-cols-4">
                                        <div className="rounded-xl bg-white p-6 shadow-md">
                                            <div className="text-3xl font-bold text-blue-600">📈</div>
                                            <div className="mt-2 text-sm text-gray-600">Kunjungan Hari Ini</div>
                                        </div>
                                        <div className="rounded-xl bg-white p-6 shadow-md">
                                            <div className="text-3xl font-bold text-green-600">💵</div>
                                            <div className="mt-2 text-sm text-gray-600">Pendapatan Hari Ini</div>
                                        </div>
                                        <div className="rounded-xl bg-white p-6 shadow-md">
                                            <div className="text-3xl font-bold text-orange-600">⚠️</div>
                                            <div className="mt-2 text-sm text-gray-600">Stok Obat Kritis</div>
                                        </div>
                                        <div className="rounded-xl bg-white p-6 shadow-md">
                                            <div className="text-3xl font-bold text-purple-600">🏆</div>
                                            <div className="mt-2 text-sm text-gray-600">Item Terlaris</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Call to Action */}
                            <div className="bg-gradient-to-r from-indigo-600 to-purple-700 px-8 py-12 text-center text-white">
                                <div className="mx-auto max-w-3xl">
                                    <h2 className="mb-4 text-3xl font-bold">
                                        🚀 Mulai Digitalisasi Klinik Anda Hari Ini!
                                    </h2>
                                    <p className="mb-8 text-lg opacity-90">
                                        Tingkatkan efisiensi operasional klinik dengan sistem manajemen yang mudah digunakan dan powerful.
                                    </p>
                                    {!auth.user && (
                                        <Link
                                            href={route('register')}
                                            className="inline-flex items-center gap-2 rounded-xl bg-white px-8 py-4 text-lg font-semibold text-indigo-600 shadow-lg hover:bg-gray-50 transition-all transform hover:scale-105"
                                        >
                                            ✨ Daftar Gratis Sekarang
                                        </Link>
                                    )}
                                </div>
                            </div>
                        </div>

                        {/* Footer */}
                        <footer className="mt-12 text-center text-sm text-gray-600">
                            <p>
                                🏥 Sistem Manajemen Klinik - Dikembangkan dengan ❤️ untuk kemajuan kesehatan Indonesia
                            </p>
                        </footer>
                    </main>
                </div>
            </div>
        </>
    );
}