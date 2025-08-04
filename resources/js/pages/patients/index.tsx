import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Head, Link, router } from '@inertiajs/react';

interface Patient {
    id: number;
    patient_id: string;
    name: string;
    birth_date: string;
    gender: string;
    phone: string | null;
    address: string | null;
    visits_count: number;
    created_at: string;
}

interface PaginatedPatients {
    data: Patient[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}

interface Props {
    patients: PaginatedPatients;
    filters: {
        search?: string;
    };
    [key: string]: unknown;
}

export default function PatientsIndex({ patients, filters }: Props) {
    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('id-ID');
    };

    const calculateAge = (birthDate: string) => {
        const today = new Date();
        const birth = new Date(birthDate);
        let age = today.getFullYear() - birth.getFullYear();
        const monthDiff = today.getMonth() - birth.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        
        return age;
    };

    const handleSearch = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        const formData = new FormData(e.currentTarget);
        const search = formData.get('search') as string;
        
        router.get(route('patients.index'), { search }, {
            preserveState: true,
            replace: true,
        });
    };

    return (
        <>
            <Head title="Daftar Pasien" />
            
            <AppShell>
                <div className="space-y-6">
                    {/* Page Header */}
                    <div className="flex items-center justify-between">
                        <div>
                            <h1 className="text-3xl font-bold text-gray-900">👥 Daftar Pasien</h1>
                            <p className="mt-2 text-gray-600">
                                Kelola data pasien klinik Anda
                            </p>
                        </div>
                        <Link href={route('patients.create')}>
                            <Button size="lg" className="gap-2">
                                ➕ Tambah Pasien Baru
                            </Button>
                        </Link>
                    </div>

                    {/* Search and Filters */}
                    <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                        <form onSubmit={handleSearch} className="flex gap-4">
                            <div className="flex-1">
                                <input
                                    type="text"
                                    name="search"
                                    placeholder="Cari pasien berdasarkan nama, ID, atau nomor telepon..."
                                    defaultValue={filters.search || ''}
                                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                            <Button type="submit" variant="outline">
                                🔍 Cari
                            </Button>
                            {filters.search && (
                                <Button
                                    type="button"
                                    variant="ghost"
                                    onClick={() => router.get(route('patients.index'))}
                                >
                                    ✖️ Reset
                                </Button>
                            )}
                        </form>
                    </div>

                    {/* Patients List */}
                    <div className="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                        <div className="px-6 py-4 border-b border-gray-200">
                            <h2 className="text-lg font-semibold text-gray-900">
                                Total Pasien: {patients.total}
                            </h2>
                        </div>
                        
                        {patients.data.length > 0 ? (
                            <div className="overflow-x-auto">
                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                ID Pasien
                                            </th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nama Pasien
                                            </th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Umur / Jenis Kelamin
                                            </th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Kontak
                                            </th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Kunjungan
                                            </th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Terdaftar
                                            </th>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {patients.data.map((patient) => (
                                            <tr key={patient.id} className="hover:bg-gray-50">
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                                    {patient.patient_id}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="text-sm font-medium text-gray-900">
                                                        {patient.name}
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {calculateAge(patient.birth_date)} tahun / {patient.gender === 'L' ? 'Laki-laki' : 'Perempuan'}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {patient.phone || 'Tidak ada'}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        {patient.visits_count} kunjungan
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {formatDate(patient.created_at)}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div className="flex gap-2">
                                                        <Link href={route('patients.show', patient.id)}>
                                                            <Button size="sm" variant="outline">
                                                                👁️ Lihat
                                                            </Button>
                                                        </Link>
                                                        <Link href={route('patients.edit', patient.id)}>
                                                            <Button size="sm" variant="outline">
                                                                ✏️ Edit
                                                            </Button>
                                                        </Link>
                                                    </div>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        ) : (
                            <div className="px-6 py-12 text-center">
                                <div className="text-6xl mb-4">👥</div>
                                <h3 className="text-lg font-medium text-gray-900 mb-2">
                                    {filters.search ? 'Tidak ada pasien yang ditemukan' : 'Belum ada pasien'}
                                </h3>
                                <p className="text-gray-500 mb-6">
                                    {filters.search 
                                        ? 'Coba ubah kata kunci pencarian atau reset filter.'
                                        : 'Mulai dengan menambahkan pasien pertama Anda.'
                                    }
                                </p>
                                {!filters.search && (
                                    <Link href={route('patients.create')}>
                                        <Button size="lg">
                                            ➕ Tambah Pasien Baru
                                        </Button>
                                    </Link>
                                )}
                            </div>
                        )}

                        {/* Pagination */}
                        {patients.last_page > 1 && (
                            <div className="px-6 py-4 border-t border-gray-200">
                                <div className="flex items-center justify-between">
                                    <div className="text-sm text-gray-500">
                                        Menampilkan {((patients.current_page - 1) * patients.per_page) + 1} - {Math.min(patients.current_page * patients.per_page, patients.total)} dari {patients.total} pasien
                                    </div>
                                    <div className="flex gap-1">
                                        {patients.links.map((link, index) => (
                                            <button
                                                key={index}
                                                onClick={() => link.url && router.get(link.url)}
                                                disabled={!link.url}
                                                className={`px-3 py-2 text-sm border rounded-md ${
                                                    link.active
                                                        ? 'bg-blue-600 text-white border-blue-600'
                                                        : link.url
                                                        ? 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                                                        : 'bg-gray-100 text-gray-400 border-gray-300 cursor-not-allowed'
                                                }`}
                                                dangerouslySetInnerHTML={{ __html: link.label }}
                                            />
                                        ))}
                                    </div>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </AppShell>
        </>
    );
}