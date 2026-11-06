<?php

namespace App\Services;

use App\Models\PilihanJawaban;
use App\Models\SesiTesSiswa;
use App\Models\Soal;
use App\Models\Tes;

class PsikometrikService
{
    /**
     * Definisi 4 Jenis Asesmen Psikometrik BK & Dimensi Pengukurannya.
     */
    public const TIPE_VARK   = 'Belajar (VARK)';
    public const TIPE_OCEAN  = 'Pribadi (OCEAN)';
    public const TIPE_RIASEC = 'Karier (RIASEC)';
    public const TIPE_SOSIAL = 'Sosial';

    /**
     * Mendapatkan daftar lengkap jenis asesmen beserta meta dan dimensinya.
     */
    public static function getJenisAsesmen(): array
    {
        return [
            self::TIPE_VARK => [
                'kode'       => 'VARK',
                'nama'       => 'Belajar (VARK)',
                'label'      => 'Gaya Belajar (VARK)',
                'deskripsi'  => 'Mengetahui kecenderungan gaya belajar siswa (Visual, Auditori, Membaca/Menulis, Kinestetik) untuk memaksimalkan efektivitas pemahaman materi.',
                'fokus'      => 'Kecenderungan Gaya Belajar',
                'warna'      => 'sky',
                'dimensi'    => [
                    'Visual' => [
                        'nama'       => 'Visual (V)',
                        'deskripsi'  => 'Belajar lebih cepat melalui penglihatan: grafik, diagram, gambar, bagan, dan peta konsep.',
                        'karakter'   => 'Menyukai ilustrasi, warna penanda, visualisasi terstruktur, dan catatan berpola rapi.',
                    ],
                    'Auditori' => [
                        'nama'       => 'Auditori (A)',
                        'deskripsi'  => 'Belajar lebih optimal melalui pendengaran: ceramah lisan, diskusi, debat, dan pengulangan verbal.',
                        'karakter'   => 'Peka intonasi suara, mudah mengingat apa yang didiskusikan, suka belajar sambil mendengarkan atau membaca bersuara.',
                    ],
                    'Reading' => [
                        'nama'       => 'Reading / Writing (R)',
                        'deskripsi'  => 'Belajar efektif melalui kata-kata tertulis: membaca teks rujukan, rangkuman, artikel, dan glosarium.',
                        'karakter'   => 'Senang membuat catatan lengkap, membaca buku acuan, menyusun daftar poin, dan menuangkan ide dalam tulisan.',
                    ],
                    'Kinestetik' => [
                        'nama'       => 'Kinestetik (K)',
                        'deskripsi'  => 'Belajar terbaik melalui tindakan fisik: praktik langsung, simulasi, peragaan, dan uji coba nyata.',
                        'karakter'   => 'Suka bergerak, belajar sambil menyentuh objek/alat peraga, cepat bosan jika hanya duduk diam mendengarkan teori.',
                    ],
                ],
            ],

            self::TIPE_OCEAN => [
                'kode'       => 'OCEAN',
                'nama'       => 'Pribadi (OCEAN)',
                'label'      => 'Kepribadian (OCEAN / Big Five)',
                'deskripsi'  => 'Memberikan gambaran karakter dan kecenderungan kepribadian siswa guna memahami kebiasaan, adaptasi, dan dinamika emosinya.',
                'fokus'      => 'Karakter & Dinamika Kepribadian',
                'warna'      => 'indigo',
                'dimensi'    => [
                    'Openness' => [
                        'nama'       => 'Openness (O)',
                        'deskripsi'  => 'Tingkat keterbukaan terhadap pengalaman baru, imajinasi, eksplorasi ide kreatif, dan rasa ingin tahu intelektual.',
                        'karakter'   => 'Kreatif, imajinatif, terbuka pada sudut pandang alternatif, suka mempelajari hal-hal baru yang unik.',
                    ],
                    'Conscientiousness' => [
                        'nama'       => 'Conscientiousness (C)',
                        'deskripsi'  => 'Derajat kedisiplinan, ketelitian, tanggung jawab, keteraturan perencanaan, dan kegigihan mencapai target.',
                        'karakter'   => 'Terorganisir, disiplin waktu, fokus pada tujuan, berhati-hati dalam bertindak, dan pekerja keras.',
                    ],
                    'Extraversion' => [
                        'nama'       => 'Extraversion (E)',
                        'deskripsi'  => 'Orientasi energi dalam berinteraksi sosial, keramahan, keberanian bicara di muka umum, dan antusiasme kelompok.',
                        'karakter'   => 'Energik saat bersama orang lain, komunikatif, percaya diri, optimis, dan aktif mencari pertemanan baru.',
                    ],
                    'Agreeableness' => [
                        'nama'       => 'Agreeableness (A)',
                        'deskripsi'  => 'Kehangatan relasi, empati tulus, sikap kooperatif, toleransi, dan kesediaan membantu sesama.',
                        'karakter'   => 'Penyayang, mudah mempercayai orang lain, menghindari konflik destruktif, suka menolong dan bekerja sama.',
                    ],
                    'Neuroticism' => [
                        'nama'       => 'Emotional Stability (N)',
                        'deskripsi'  => 'Kestabilan emosi dan ketahanan psikologis siswa saat menghadapi tekanan, rasa cemas, dan situasi sulit.',
                        'karakter'   => 'Skor tinggi menunjukkan ketenangan dan kontrol diri baik; skor rendah memerlukan pendampingan regulasi emosi.',
                    ],
                ],
            ],

            self::TIPE_RIASEC => [
                'kode'       => 'RIASEC',
                'nama'       => 'Karier (RIASEC)',
                'label'      => 'Minat & Arah Karier (RIASEC)',
                'deskripsi'  => 'Membantu siswa mengenali minat bakat, arah studi lanjut, dan rumpun karier masa depan berdasarkan Teori Holland Codes.',
                'fokus'      => 'Eksplorasi Karier & Studi Lanjut',
                'warna'      => 'amber',
                'dimensi'    => [
                    'Realistic' => [
                        'nama'       => 'Realistic (R)',
                        'deskripsi'  => 'Minat terhadap aktivitas praktis, mekanikal, pengoperasian mesin/alat, alam bebas, dan pekerjaan lapangan.',
                        'karakter'   => 'Suka bekerja dengan tangan, terampil teknik, menyukai hasil konkret nyata daripada teori abstrak.',
                    ],
                    'Investigative' => [
                        'nama'       => 'Investigative (I)',
                        'deskripsi'  => 'Minat terhadap riset ilmiah, pemecahan masalah rumit, logika analitis, dan observasi data.',
                        'karakter'   => 'Kritis, analitis, suka bertanya "mengapa", senang bereksperimen dan menguraikan persoalan saintifik.',
                    ],
                    'Artistic' => [
                        'nama'       => 'Artistic (A)',
                        'deskripsi'  => 'Minat terhadap ekspresi kreatif, seni, desain visual, musik, sastra, dan kebebasan berpikir bebas aturan kaku.',
                        'karakter'   => 'Orisinal, intuitif, berjiwa seni tinggi, ekspresif, tidak menyukai rutinitas yang monoton.',
                    ],
                    'Social' => [
                        'nama'       => 'Social (S)',
                        'deskripsi'  => 'Minat mendidik, membimbing, merawat, menolong sesama, dan memfasilitasi perkembangan orang lain.',
                        'karakter'   => 'Empatik, komunikatif, sabar mendengarkan keluh kesah, berorientasi pada pelayanan masyarakat.',
                    ],
                    'Enterprising' => [
                        'nama'       => 'Enterprising (E)',
                        'deskripsi'  => 'Minat dalam memimpin, bernegosiasi, membujuk orang lain, mengambil risiko, dan kewirausahaan.',
                        'karakter'   => 'Percaya diri tinggi, persuasif, memiliki inisiatif bisnis, berani memimpin tim mencapai target besar.',
                    ],
                    'Conventional' => [
                        'nama'       => 'Conventional (C)',
                        'deskripsi'  => 'Minat pada keteraturan data terstruktur, akurasi administrasi, kepatuhan prosedur, dan manajemen arsip rapi.',
                        'karakter'   => 'Rapi, teliti, taat regulasi, andal dalam urusan angka dan arsip, menyukai pola kerja yang pasti.',
                    ],
                ],
            ],

            self::TIPE_SOSIAL => [
                'kode'       => 'SOSIAL',
                'nama'       => 'Sosial',
                'label'      => 'Kemampuan Sosial & Interpersonal',
                'deskripsi'  => 'Mengetahui kecakapan komunikasi, kepekaan empati, kerja sama tim, dan kematangan dalam menyelesaikan konflik pertemanan.',
                'fokus'      => 'Kecakapan Relasi & Lingkungan Sosial',
                'warna'      => 'emerald',
                'dimensi'    => [
                    'Komunikasi' => [
                        'nama'       => 'Komunikasi Asertif',
                        'deskripsi'  => 'Keterampilan menyampaikan pendapat dan perasaan secara santun, jelas, lugas, serta mendengarkan dengan penuh perhatian.',
                        'karakter'   => 'Mampu berbicara tanpa menyinggung perasaan lawan bicara, mendengarkan aktif, dan berani mengutarakan ide.',
                    ],
                    'Empati' => [
                        'nama'       => 'Empati & Kepedulian',
                        'deskripsi'  => 'Kepekaan merasakan dan memahami kondisi emosional serta perspektif orang lain tanpa menghakimi.',
                        'karakter'   => 'Tanggap ketika teman membutuhkan pertolongan, menghargai perbedaan, dan menjadi pendengar yang hangat.',
                    ],
                    'Kerja Sama' => [
                        'nama'       => 'Kerja Sama Tim',
                        'deskripsi'  => 'Kemampuan berkoordinasi, berbagi tanggung jawab secara adil, dan mengutamakan keberhasilan kelompok.',
                        'karakter'   => 'Fleksibel dalam tim, mendukung rekan kerja kelompok, dan menghargai kontribusi seluruh anggota.',
                    ],
                    'Resolusi Konflik' => [
                        'nama'       => 'Penyelesaian Konflik',
                        'deskripsi'  => 'Kematangan sikap dalam mendamaikan perselisihan, mengontrol amarah, dan mencari solusi yang adil bagi semua pihak.',
                        'karakter'   => 'Kepala dingin saat berselisih, bersedia berkompromi, dan mengedepankan musyawarah mufakat.',
                    ],
                ],
            ],
        ];
    }

    /**
     * Mendeteksi jenis asesmen psikometrik dari sebuah objek Tes.
     */
    public static function deteksiJenis(Tes $tes): string
    {
        $kategoriNama = $tes->kategori->nama_kategori ?? '';
        $judul        = $tes->judul;

        if (stripos($kategoriNama, 'VARK') !== false || stripos($judul, 'VARK') !== false || stripos($kategoriNama, 'Gaya Belajar') !== false) {
            return self::TIPE_VARK;
        }
        if (stripos($kategoriNama, 'OCEAN') !== false || stripos($judul, 'OCEAN') !== false || stripos($kategoriNama, 'Pribadi') !== false || stripos($kategoriNama, 'Kepribadian') !== false) {
            return self::TIPE_OCEAN;
        }
        if (stripos($kategoriNama, 'RIASEC') !== false || stripos($judul, 'RIASEC') !== false || stripos($kategoriNama, 'Karier') !== false || stripos($kategoriNama, 'Karir') !== false) {
            return self::TIPE_RIASEC;
        }
        if (stripos($kategoriNama, 'Sosial') !== false || stripos($judul, 'Sosial') !== false) {
            return self::TIPE_SOSIAL;
        }

        return self::TIPE_VARK; // Default fallback
    }

    /**
     * Mendapatkan daftar dimensi untuk tipe tertentu.
     */
    public static function getDimensiList(string $tipe): array
    {
        $semua = self::getJenisAsesmen();
        return $semua[$tipe]['dimensi'] ?? $semua[self::TIPE_VARK]['dimensi'];
    }

    /**
     * Mengekstrak nama dimensi dari teks pertanyaan soal.
     * Menggunakan penanda: [Dimensi: NamaDimensi]
     */
    public static function ekstrakDimensi(string $pertanyaan, string $tipe): string
    {
        if (preg_match('/\[Dimensi:\s*([^\]]+)\]/i', $pertanyaan, $matches)) {
            $dim = trim($matches[1]);
            $list = self::getDimensiList($tipe);
            foreach ($list as $key => $info) {
                if (strcasecmp($key, $dim) === 0 || stripos($info['nama'], $dim) !== false) {
                    return $key;
                }
            }
            return $dim;
        }

        // Fallback default ke dimensi pertama
        $list = self::getDimensiList($tipe);
        return array_key_first($list);
    }

    /**
     * Membersihkan teks pertanyaan dari tag [Dimensi: ...] untuk ditampilkan ke siswa.
     */
    public static function bersihkanPertanyaan(string $pertanyaan): string
    {
        return trim(preg_replace('/\[Dimensi:\s*[^\]]+\]/i', '', $pertanyaan));
    }

    /**
     * Menyematkan tag dimensi ke teks pertanyaan sebelum disimpan ke database.
     */
    public static function sematkanDimensi(string $pertanyaan, string $dimensi): string
    {
        $bersih = self::bersihkanPertanyaan($pertanyaan);
        return "[Dimensi: {$dimensi}] " . $bersih;
    }

    /**
     * Menganalisis Keseimbangan Dimensi Soal pada sebuah Paket Tes.
     * Sistem memastikan setiap dimensi memiliki jumlah soal yang sama/seimbang.
     */
    public static function analisisKeseimbangan(Tes $tes): array
    {
        $tipe = self::deteksiJenis($tes);
        $dimensiList = self::getDimensiList($tipe);
        
        // Inisialisasi hitungan 0 untuk seluruh dimensi
        $counts = [];
        foreach (array_keys($dimensiList) as $dimKey) {
            $counts[$dimKey] = 0;
        }

        // Hitung soal pada tiap dimensi
        foreach ($tes->soals as $soal) {
            $dim = self::ekstrakDimensi($soal->pertanyaan, $tipe);
            if (!isset($counts[$dim])) {
                $counts[$dim] = 0;
            }
            $counts[$dim]++;
        }

        $totalSoal = $tes->soals->count();
        $values = array_values($counts);
        $min = count($values) ? min($values) : 0;
        $max = count($values) ? max($values) : 0;

        $isKosong   = ($totalSoal === 0);
        $isSeimbang = (!$isKosong && ($min === $max) && $min > 0);

        // Rekomendasi tindakan jika belum seimbang
        $rekomendasi = [];
        if ($isKosong) {
            $rekomendasi[] = "Paket asesmen ini belum memiliki soal. Masukkan soal secara seimbang untuk setiap dimensi (" . implode(', ', array_keys($dimensiList)) . ") atau gunakan fitur 'Template Soal Rekomendasi'.";
        } elseif (!$isSeimbang) {
            $targetPerDimensi = max(1, $max);
            foreach ($counts as $dimKey => $cnt) {
                $kekurangan = $targetPerDimensi - $cnt;
                if ($kekurangan > 0) {
                    $rekomendasi[] = "Dimensi <strong>{$dimKey}</strong> masih kekurangan <strong>{$kekurangan} butir soal</strong> agar seimbang dengan dimensi lainnya (Target: {$targetPerDimensi} butir per dimensi).";
                }
            }
        }

        return [
            'tipe'               => $tipe,
            'dimensi_list'       => $dimensiList,
            'counts'             => $counts,
            'total_soal'         => $totalSoal,
            'is_seimbang'        => $isSeimbang,
            'is_kosong'          => $isKosong,
            'min_count'          => $min,
            'max_count'          => $max,
            'target_per_dimensi' => max(1, $max),
            'rekomendasi'        => $rekomendasi,
        ];
    }

    /**
     * Template Butir Soal Standar & Seimbang untuk Masing-masing Jenis Asesmen.
     */
    public static function getTemplateSoal(string $tipe): array
    {
        $opsiLikertStandar = [
            'Sangat Sesuai / Selalu',
            'Sesuai / Sering',
            'Cukup / Kadang-kadang',
            'Kurang Sesuai / Jarang',
            'Tidak Sesuai / Tidak Pernah',
        ];

        switch ($tipe) {
            case self::TIPE_VARK:
                return [
                    [
                        'dimensi'    => 'Visual',
                        'pertanyaan' => 'Ketika guru menjelaskan materi pelajaran baru, saya lebih mudah memahaminya jika ada diagram, gambar, bagan grafis, atau tayangan slide video.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Visual',
                        'pertanyaan' => 'Saat merangkum materi ujian, saya suka menggunakan spidol warna-warni, membuat peta konsep (mind mapping), atau menggarisbawahi poin penting agar mudah diingat.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Auditori',
                        'pertanyaan' => 'Saya lebih cepat menangkap isi pelajaran dengan mendengarkan penjelasan langsung dari guru atau berdiskusi tanya-jawab dengan teman sekelas.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Auditori',
                        'pertanyaan' => 'Saat belajar sendiri di rumah, saya sering membaca materi dengan suara terdengar perlahan atau menjelaskan kembali materi tersebut kepada diri sendiri.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Reading',
                        'pertanyaan' => 'Saya lebih menyukai belajar mandiri dengan membaca modul, buku teks cetak, atau artikel rujukan terstruktur daripada sekadar mendengarkan cerita.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Reading',
                        'pertanyaan' => 'Saya merasa sangat terbantu jika memiliki catatan tertulis yang terperinci, daftar istilah (glosarium), dan latihan soal berbentuk teks uraian.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Kinestetik',
                        'pertanyaan' => 'Saya cepat paham materi pelajaran jika langsung mempraktikkannya, melakukan eksperimen lab, atau memegang alat peraga secara nyata.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Kinestetik',
                        'pertanyaan' => 'Saya merasa gelisah atau mudah mengantuk jika harus duduk diam terlalu lama mendengarkan teori tanpa ada aktivitas fisik atau latihan simulasi.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                ];

            case self::TIPE_OCEAN:
                return [
                    [
                        'dimensi'    => 'Openness',
                        'pertanyaan' => 'Saya senang mencoba hal-hal baru yang belum pernah saya pelajari sebelumnya dan tertarik mengeksplorasi ide-ide kreatif yang out-of-the-box.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Openness',
                        'pertanyaan' => 'Saya memiliki rasa ingin tahu yang besar terhadap fenomena alam, seni, filsafat, atau budaya berbeda di luar kebiasaan saya.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Conscientiousness',
                        'pertanyaan' => 'Saya selalu menyusun jadwal belajar, menyelesaikan tugas sebelum batas waktu (deadline), dan memeriksa ulang ketelitian hasil tugas saya.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Conscientiousness',
                        'pertanyaan' => 'Saya gigih dan pantang menyerah menyelesaikan pekerjaan sekolah sampai tuntas, meskipun tugas tersebut membutuhkan waktu dan ketekunan tinggi.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Extraversion',
                        'pertanyaan' => 'Saya merasa bersemangat dan ceria ketika berkumpul bersama banyak teman serta mudah memulai percakapan dengan orang baru.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Extraversion',
                        'pertanyaan' => 'Saya percaya diri ketika diminta berbicara di depan kelas atau menjadi pemimpin kelompok dalam kegiatan sekolah.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Agreeableness',
                        'pertanyaan' => 'Saya merasa mudah berempati dan tergerak membantu teman yang sedang mengalami kesulitan, tanpa mengharapkan pamrih.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Agreeableness',
                        'pertanyaan' => 'Saya lebih mengutamakan kekompakan dan kerukunan bersama daripada memaksakan kehendak pribadi dalam pergaulan sehari-hari.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Neuroticism',
                        'pertanyaan' => 'Saya dapat mengendalikan rasa panik dengan tenang dan berpikir jernih meskipun menghadapi ujian mendadak atau situasi penuh tekanan.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Neuroticism',
                        'pertanyaan' => 'Saya jarang merasa cemas berlebihan dan mampu bangkit kembali dengan cepat ketika menghadapi kegagalan nilai tugas.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                ];

            case self::TIPE_RIASEC:
                return [
                    [
                        'dimensi'    => 'Realistic',
                        'pertanyaan' => 'Saya tertarik mempelajari cara kerja mesin, merakit komponen komputer/elektronik, bertukang, atau bekerja di lapangan terbuka.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Realistic',
                        'pertanyaan' => 'Saya lebih suka mengerjakan hal-hal praktis dengan alat fisik daripada hanya mendiskusikan konsep teori abstrak di atas kertas.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Investigative',
                        'pertanyaan' => 'Saya senang membaca artikel sains, menganalisis data grafik, memecahkan teka-teki logika, dan mencari tahu alasan logis di balik suatu masalah.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Investigative',
                        'pertanyaan' => 'Saya bercita-cita mendalami bidang penelitian, kedokteran, teknologi data, atau riset akademis yang membutuhkan pemikiran kritis mendalam.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Artistic',
                        'pertanyaan' => 'Saya menyukai aktivitas mendesain grafis, menggambar, menulis cerita fiksi, bermusik, atau fotografi sebagai sarana mengekspresikan diri.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Artistic',
                        'pertanyaan' => 'Saya mendambakan karier masa depan yang memberi ruang kebebasan berkarya tanpa terlalu banyak aturan birokrasi yang mengekang.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Social',
                        'pertanyaan' => 'Saya merasa puas dan bahagia jika dapat mendampingi, mengajari pelajaran, atau memberikan nasihat positif kepada teman sebaya.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Social',
                        'pertanyaan' => 'Saya tertarik pada profesi yang bersentuhan langsung dengan kemanusiaan, seperti konselor, guru, perawat, atau relawan sosial.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Enterprising',
                        'pertanyaan' => 'Saya tertarik pada dunia bisnis, memulai usaha wirausaha mandiri, mempromosikan produk, dan memimpin tim untuk mencapai target.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Enterprising',
                        'pertanyaan' => 'Saya pandai meyakinkan orang lain dan bernegosiasi agar ide atau rencana saya disepakati oleh anggota kelompok.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Conventional',
                        'pertanyaan' => 'Saya menyukai pekerjaan yang menuntut ketelitian tinggi, seperti menyusun laporan keuangan, mengelola tabel data rapi, atau arsip administrasi.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Conventional',
                        'pertanyaan' => 'Saya merasa nyaman bekerja dalam sistem yang memiliki aturan, prosedur operasional standar (SOP), dan instruksi kerja yang jelas.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                ];

            case self::TIPE_SOSIAL:
                return [
                    [
                        'dimensi'    => 'Komunikasi',
                        'pertanyaan' => 'Saya mampu menyampaikan pendapat pribadi dengan santun tanpa merendahkan pendapat teman, serta bersedia mendengarkan sampai tuntas sebelum menanggapi.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Komunikasi',
                        'pertanyaan' => 'Saya berani mengklarifikasi kesalahpahaman secara langsung dengan tutur kata yang baik dan tidak membicarakan orang di belakang.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Empati',
                        'pertanyaan' => 'Saya dapat menangkap perasaan sedih atau kecewa teman saya dari raut wajahnya meskipun ia tidak menceritakannya secara terbuka.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Empati',
                        'pertanyaan' => 'Saya berusaha menempatkan diri pada posisi orang lain sebelum memberikan penilaian atau komentar terhadap perilakunya.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Kerja Sama',
                        'pertanyaan' => 'Dalam kerja kelompok kelas, saya bersedia berbagi tugas secara adil dan proaktif membantu rekan yang tertinggal demi kesuksesan tugas bersama.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Kerja Sama',
                        'pertanyaan' => 'Saya menghormati kesepakatan kelompok dan menahan ego pribadi jika ada ide bersama yang lebih bermanfaat untuk kelas.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Resolusi Konflik',
                        'pertanyaan' => 'Saat terjadi perbedaan pendapat atau pertengkaran antarteman, saya berinisiatif menenangkan suasana dan membantu mencari jalan tengah damai.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                    [
                        'dimensi'    => 'Resolusi Konflik',
                        'pertanyaan' => 'Saya tidak mudah terpancing emosi amarah ketika ada yang mengkritik saya, melainkan memilih bermusyawarah dengan kepala dingin.',
                        'pilihan'    => $opsiLikertStandar,
                    ],
                ];

            default:
                return [];
        }
    }

    /**
     * Otomatis mengisi butir-butir soal standar psikometrik seimbang ke dalam paket tes.
     */
    public static function terapkanTemplateKeTes(Tes $tes): int
    {
        $tipe = self::deteksiJenis($tes);
        $template = self::getTemplateSoal($tipe);

        $soalDibuat = 0;
        foreach ($template as $item) {
            $pertanyaanBerdimensi = self::sematkanDimensi($item['pertanyaan'], $item['dimensi']);

            $soal = Soal::create([
                'id_tes'      => $tes->id_tes,
                'pertanyaan'  => $pertanyaanBerdimensi,
                'tipe_soal'   => 'PG',
                'bobot_nilai' => 5, // Bobot skala likert
            ]);

            // Buat pilihan jawaban (Skala Likert 5 s/d 1)
            foreach ($item['pilihan'] as $idx => $teksOpsi) {
                PilihanJawaban::create([
                    'id_soal'      => $soal->id_soal,
                    'teks_pilihan' => $teksOpsi,
                    'is_benar'     => ($idx === 0), // Default kunci opsi teratas
                ]);
            }
            $soalDibuat++;
        }

        return $soalDibuat;
    }

    /**
     * Menganalisis Sesi Pengerjaan Siswa Secara Komprehensif:
     * Menghasilkan Profil Naratif, Dimensi Dominan, Potensi Kekuatan, Rekomendasi Guru BK,
     * serta Tindak Lanjut Konseling Orang Tua.
     */
    public static function analisisHasilSiswa(SesiTesSiswa $sesi): array
    {
        $tes = $sesi->tes;
        $tipe = self::deteksiJenis($tes);
        $dimensiList = self::getDimensiList($tipe);

        // Siapkan accumulator skor per dimensi
        $skorDimensi = [];
        $maksDimensi = [];
        foreach (array_keys($dimensiList) as $dimKey) {
            $skorDimensi[$dimKey] = 0;
            $maksDimensi[$dimKey] = 0;
        }

        $sesi->loadMissing(['jawabanSiswas.pilihanJawaban', 'jawabanSiswas.soal.pilihanJawabans']);

        foreach ($sesi->jawabanSiswas as $jawaban) {
            $soal = $jawaban->soal;
            if (!$soal) continue;

            $dim = self::ekstrakDimensi($soal->pertanyaan, $tipe);
            if (!isset($skorDimensi[$dim])) {
                $skorDimensi[$dim] = 0;
                $maksDimensi[$dim] = 0;
            }

            // Hitung poin Likert berdasarkan urutan opsi pilihan (0 = 5 poin, 1 = 4 poin, dst.)
            $poin = 3; // Default netral
            if ($jawaban->id_pilihan && $soal->pilihanJawabans) {
                $pilihans = $soal->pilihanJawabans->values();
                $idx = $pilihans->search(fn($p) => $p->id_pilihan == $jawaban->id_pilihan);
                if ($idx !== false) {
                    $totalOpsi = max(1, count($pilihans));
                    $poin = max(1, $totalOpsi - $idx); // 5, 4, 3, 2, 1
                }
            }

            $skorDimensi[$dim] += $poin;
            $maksDimensi[$dim] += 5; // Maksimal skala per soal adalah 5
        }

        // Hitung persentase capaian per dimensi
        $persentaseDimensi = [];
        foreach ($skorDimensi as $dimKey => $skor) {
            $maks = max(1, $maksDimensi[$dimKey]);
            $persentaseDimensi[$dimKey] = round(($skor / $maks) * 100, 1);
        }

        // Urutkan dimensi dari skor tertinggi ke terendah
        arsort($persentaseDimensi);
        $rankingDimensi = array_keys($persentaseDimensi);
        $dimensiPrimer = $rankingDimensi[0] ?? array_key_first($dimensiList);
        $dimensiSekunder = $rankingDimensi[1] ?? ($rankingDimensi[0] ?? array_key_first($dimensiList));

        // Buat narasi psikometrik mendalam
        $narasi = self::bangunProfilNaratif($tipe, $dimensiPrimer, $dimensiSekunder, $persentaseDimensi, $sesi->siswa->nama_siswa ?? 'Siswa');

        return [
            'tipe_asesmen'        => $tipe,
            'nama_siswa'          => $sesi->siswa->nama_siswa ?? '-',
            'nisn'                => $sesi->siswa->nisn ?? '-',
            'dimensi_primer'      => $dimensiPrimer,
            'dimensi_sekunder'    => $dimensiSekunder,
            'label_primer'        => $dimensiList[$dimensiPrimer]['nama'] ?? $dimensiPrimer,
            'label_sekunder'      => $dimensiList[$dimensiSekunder]['nama'] ?? $dimensiSekunder,
            'skor_dimensi'        => $skorDimensi,
            'maks_dimensi'        => $maksDimensi,
            'persentase_dimensi'  => $persentaseDimensi,
            'dimensi_info'        => $dimensiList,
            'judul_profil'        => $narasi['judul'],
            'profil_naratif'      => $narasi['deskripsi'],
            'potensi_kekuatan'    => $narasi['kekuatan'],
            'area_pengembangan'   => $narasi['pengembangan'],
            'rekomendasi_siswa'   => $narasi['rekomendasi_siswa'],
            'strategi_guru_bk'    => $narasi['strategi_guru_bk'],
            'tindak_lanjut_ortu'  => $narasi['tindak_lanjut_ortu'],
        ];
    }

    /**
     * Membangun narasi laporan diagnostik berdasarkan profil dominan.
     */
    private static function bangunProfilNaratif(string $tipe, string $primer, string $sekunder, array $skor, string $namaSiswa): array
    {
        switch ($tipe) {
            case self::TIPE_VARK:
                return [
                    'judul' => "Profil Gaya Belajar: Dominan {$primer} didukung {$sekunder}",
                    'deskripsi' => "Berdasarkan analisis asesmen gaya belajar VARK, {$namaSiswa} memiliki kecenderungan belajar paling kuat pada dimensi {$primer} ({$skor[$primer]}%) dengan modalitas pendukung pada dimensi {$sekunder} ({$skor[$sekunder]}%). Siswa dengan profil ini memproses informasi secara optimal ketika materi pembelajaran disajikan melalui stimulasi visual dan pengalaman interaktif yang relevan.",
                    'kekuatan' => [
                        "Daya tangkap konsep lebih cepat jika disertai demonstrasi visual dan keterlibatan aktif.",
                        "Kemampuan mengingat detail materi pelajaran dengan mengaitkan pada pola representasi representatif.",
                        "Fleksibilitas memadukan cara belajar mandiri dengan elaborasi tugas berkelompok.",
                    ],
                    'pengembangan' => [
                        "Perlu melatih kesabaran saat menghadapi materi pelajaran yang bersifat hafalan teks tebal panjang.",
                        "Membiasakan diri menyusun rangkuman mandiri secara terstruktur sebelum sesi ujian.",
                    ],
                    'rekomendasi_siswa' => [
                        "Gunakan teknik mind mapping dan spidol warna untuk menandai kata kunci pelajaran.",
                        "Lakukan visualisasi skema atau tonton video tutorial pembelajaran untuk topik-topik rumit.",
                        "Praktikkan latihan soal secara berkala dengan simulasi waktu nyata.",
                    ],
                    'strategi_guru_bk' => [
                        "Fasilitasi siswa dengan media bimbingan interaktif, modul infografis, dan sesi diskusi studi kasus.",
                        "Berikan ruang bagi siswa untuk mempresentasikan pemahamannya melalui peta ide atau karya visual.",
                        "Bimbing siswa memilih teknik belajar efektif saat menghadapi masa persiapan asesmen akhir semester.",
                    ],
                    'tindak_lanjut_ortu' => [
                        "Sediakan ruang belajar di rumah yang bebas distraksi visual berlebihan dan dilengkapi sarana pencatatan kreatif.",
                        "Ajak ananda berdiskusi ringan mengenai apa yang dipelajarinya di sekolah dengan menanyakan 'bagaimana gambarannya'.",
                        "Dukung ananda untuk mengombinasikan belajar buku teks dengan materi edukasi multimedia berkualitas.",
                    ],
                ];

            case self::TIPE_OCEAN:
                return [
                    'judul' => "Profil Karakter Pribadi: Karakter Unggul {$primer} & {$sekunder}",
                    'deskripsi' => "Hasil evaluasi psikometrik kepribadian Big Five (OCEAN) mengindikasikan bahwa {$namaSiswa} memiliki karakter menonjol pada dimensi {$primer} ({$skor[$primer]}%) berpadu dengan dimensi {$sekunder} ({$skor[$sekunder]}%). Profil ini mencerminkan pribadi yang adaptif, memiliki integritas dalam bertindak, serta mampu mengelola interaksi sosial dengan baik.",
                    'kekuatan' => [
                        "Kematangan sikap dan komitmen tinggi dalam menuntaskan amanah dan tugas sekolah.",
                        "Sikap terbuka dan kemampuan beradaptasi di tengah dinamika lingkungan baru.",
                        "Potensi kepemimpinan dan pengaruh positif terhadap rekan-rekan sebaya di kelas.",
                    ],
                    'pengembangan' => [
                        "Perlu mengelola ekspektasi diri agar terhindar dari stres perfeksionisme yang berlebihan.",
                        "Tingkatkan keberanian dalam menyampaikan batasan asertif jika merasa beban tugas terlalu menumpuk.",
                    ],
                    'rekomendasi_siswa' => [
                        "Jadwalkan waktu istirahat secara seimbang di sela-sela rutinitas akademik padat.",
                        "Terus asah bakat kepemimpinan dengan aktif dalam kegiatan ekstrakurikuler sekolah.",
                        "Tulis jurnal refleksi diri mingguan untuk memantau kemajuan pribadi dan kestabilan emosi.",
                    ],
                    'strategi_guru_bk' => [
                        "Libatkan siswa dalam peran-peran tanggung jawab kelas atau kepanitiaan OSIS/kegiatan bimbingan sebaya.",
                        "Lakukan sesi konseling individu berkala untuk mengapresiasi pencapaian dan mengawal kestabilan emosinya.",
                        "Bantu siswa menyusun target jangka pendek dan jangka panjang yang realistis serta bermakna.",
                    ],
                    'tindak_lanjut_ortu' => [
                        "Berikan apresiasi tulus atas usaha dan proses perjuangan ananda, bukan semata-mata terpaku pada nilai akhir.",
                        "Jadilah pendengar yang suportif saat ananda mengekspresikan kekhawatiran atau kejenuhan belajarnya.",
                        "Jaga komunikasi hangat dan hindari membandingkan capaian ananda dengan saudara atau anak lain.",
                    ],
                ];

            case self::TIPE_RIASEC:
                return [
                    'judul' => "Profil Minat Karier: Klaster {$primer} & {$sekunder}",
                    'deskripsi' => "Analisis minat karier Holland Codes (RIASEC) menunjukkan kecenderungan kuat {$namaSiswa} pada klaster {$primer} ({$skor[$primer]}%) dan {$sekunder} ({$skor[$sekunder]}%). Siswa memiliki antusiasme tinggi untuk mendalami disiplin ilmu dan rumpun profesi yang memberikan ruang bagi eksplorasi analitis, pemecahan masalah nyata, dan kontribusi karya konkret.",
                    'kekuatan' => [
                        "Ketertarikan intrinsik yang kuat pada bidang keahlian spesifik yang diminatinya.",
                        "Kemampuan memfokuskan energi pada proyek-proyek yang selaras dengan bakat alaminya.",
                        "Potensi unggul dalam jalur studi lanjut terapan, sains-teknologi, atau industri kreatif strategis.",
                    ],
                    'pengembangan' => [
                        "Perlu memperluas wawasan informasi mengenai ragam profesi masa depan yang sedang berkembang pesat.",
                        "Tingkatkan keterampilan komunikasi non-teknis (soft skills) untuk mendukung kesiapan karier.",
                    ],
                    'rekomendasi_siswa' => [
                        "Telusuri program studi perguruan tinggi atau kejuruan yang linear dengan rumpun minat {$primer}-{$sekunder}.",
                        "Ikuti webinar inspirasi karier, lomba kompetensi kejuruan, atau program magang singkat sekolah.",
                        "Konsultasikan secara rutin hasil peminatan ini kepada Guru BK sekolah untuk pemetaan studi lanjut.",
                    ],
                    'strategi_guru_bk' => [
                        "Arahkan siswa ke bank informasi bimbingan karier HaloBK mengenai kurikulum dan prospek jurusan kuliah.",
                        "Bantu siswa menyusun matriks komparasi program studi yang sesuai antara minat, bakat, dan kapasitas akademik.",
                        "Fasilitasi komunikasi peminatan antara siswa dengan orang tua agar tercapai keselarasan pilihan studi.",
                    ],
                    'tindak_lanjut_ortu' => [
                        "Dukung eksplorasi minat karier ananda dengan membuka ruang diskusi mengenai cita-cita masa depannya.",
                        "Bersama Guru BK, hadiri sesi konsultasi peminatan untuk memahami potensi terbaik putra-putri Anda.",
                        "Bantu ananda mencari informasi mengenai prospek jalur masuk perguruan tinggi yang relevan dengan minatnya.",
                    ],
                ];

            case self::TIPE_SOSIAL:
                return [
                    'judul' => "Profil Kecakapan Sosial: Unggul pada {$primer} & {$sekunder}",
                    'deskripsi' => "Evaluasi kecakapan sosial mengidentifikasi {$namaSiswa} memiliki kompetensi relasi interpersonal yang kuat pada aspek {$primer} ({$skor[$primer]}%) dan {$sekunder} ({$skor[$sekunder]}%). Siswa menunjukkan kematangan dalam berinteraksi, peka terhadap lingkungan sekitar, serta mampu menjadi perekat keharmonisan dalam dinamika kelompok kelas.",
                    'kekuatan' => [
                        "Kemampuan menjalin komunikasi hangat dan membangun rasa saling percaya dengan teman sebaya.",
                        "Kepekaan empati tinggi dalam memberikan dukungan emosional kepada orang lain.",
                        "Sikap dewasa dan kepala dingin saat menghadapi gesekan perbedaan pendapat di kelompok.",
                    ],
                    'pengembangan' => [
                        "Perlu memperkuat ketegasan asertif agar tidak mudah dimanfaatkan dalam pembagian beban tugas tim.",
                        "Latih keterampilan mediasi formal untuk membantu resolusi konflik yang lebih luas di sekolah.",
                    ],
                    'rekomendasi_siswa' => [
                        "Pertahankan kebiasaan menjadi pendengar yang baik dan komunikator yang santun dalam pergaulan.",
                        "Berani menyuarakan kejujuran dan kebenaran secara konstruktif demi kebaikan bersama.",
                        "Ikuti organisasi kesiswaan untuk memperluas jejaring sosial dan kepemimpinan kemanusiaan.",
                    ],
                    'strategi_guru_bk' => [
                        "Posisikan siswa sebagai duta sebaya (peer counselor) dalam program bimbingan konseling kelas.",
                        "Ajak siswa berpartisipasi aktif dalam kegiatan sosial, bakti amal, atau kampanye anti-bullying di sekolah.",
                        "Berikan penguatan positif atas kontribusinya dalam menjaga suasana kelas tetap rukun dan kondusif.",
                    ],
                    'tindak_lanjut_ortu' => [
                        "Tanamkan nilai-nilai kebersamaan dan empati dalam keluarga melalui kebiasaan berkumpul dan berbagi cerita.",
                        "Apresiasi ketulusan ananda dalam membantu teman dan dorong ia untuk tetap percaya diri.",
                        "Diskusikan situasi sosial yang dihadapi ananda dengan bijaksana tanpa memicu prasangka negatif.",
                    ],
                ];

            default:
                return [
                    'judul' => "Laporan Diagnostik Asesmen: {$primer}",
                    'deskripsi' => "Siswa menunjukkan perolehan dominan pada dimensi {$primer} ({$skor[$primer]}%). Profil ini menjadi acuan bermakna bagi Guru BK dalam memberikan intervensi bimbingan konseling terpadu.",
                    'kekuatan' => ["Konsistensi dalam menjawab butir-butir pertanyaan instrumen."],
                    'pengembangan' => ["Pengembangan bertahap pada aspek yang skornya masih di bawah rata-rata."],
                    'rekomendasi_siswa' => ["Tingkatkan motivasi belajar dan eksplorasi bakat."],
                    'strategi_guru_bk' => ["Berikan pendampingan bimbingan konseling personal."],
                    'tindak_lanjut_ortu' => ["Bekerja sama dengan pihak sekolah demi perkembangan ananda."],
                ];
        }
    }

    /**
     * Menganalisis Kondisi Kelas Secara Keseluruhan Berdasarkan Rekap Sesi Tes.
     */
    public static function analisisHasilKelas(Tes $tes): array
    {
        $tipe = self::deteksiJenis($tes);
        $dimensiList = self::getDimensiList($tipe);

        $tes->loadMissing(['sesiTesSiswas.siswa', 'sesiTesSiswas.jawabanSiswas.pilihanJawaban']);
        $sesiSelesai = $tes->sesiTesSiswas->where('status_pengerjaan', 'selesai');

        $distribusiDominan = [];
        foreach (array_keys($dimensiList) as $dimKey) {
            $distribusiDominan[$dimKey] = 0;
        }

        $siswaReports = [];
        foreach ($sesiSelesai as $sesi) {
            $analisis = self::analisisHasilSiswa($sesi);
            $primer = $analisis['dimensi_primer'];
            if (!isset($distribusiDominan[$primer])) {
                $distribusiDominan[$primer] = 0;
            }
            $distribusiDominan[$primer]++;

            $siswaReports[] = [
                'sesi'      => $sesi,
                'nama'      => $sesi->siswa->nama_siswa ?? 'Siswa',
                'nisn'      => $sesi->siswa->nisn ?? '-',
                'primer'    => $analisis['label_primer'],
                'sekunder'  => $analisis['label_sekunder'],
                'analisis'  => $analisis,
            ];
        }

        $totalSiswaSelesai = count($siswaReports);
        $persentaseDistribusi = [];
        foreach ($distribusiDominan as $dimKey => $count) {
            $persentaseDistribusi[$dimKey] = $totalSiswaSelesai > 0 ? round(($count / $totalSiswaSelesai) * 100, 1) : 0;
        }

        // Tipe paling dominan di kelas
        arsort($distribusiDominan);
        $tipeMayoritas = array_key_first($distribusiDominan);

        return [
            'tipe_asesmen'          => $tipe,
            'total_siswa_selesai'   => $totalSiswaSelesai,
            'distribusi_dominan'    => $distribusiDominan,
            'persentase_distribusi' => $persentaseDistribusi,
            'tipe_mayoritas'        => $tipeMayoritas,
            'label_mayoritas'       => $dimensiList[$tipeMayoritas]['nama'] ?? $tipeMayoritas,
            'siswa_reports'         => $siswaReports,
            'dimensi_info'          => $dimensiList,
        ];
    }
}

