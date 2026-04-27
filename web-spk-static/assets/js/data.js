/**
 * data.js
 * Logic for Data Storage and SPK Calculations
 */

// Initial Seed Data
const defaultKriteria = [
    { id: 1, kode: 'C1', nama: 'Harga Sewa', type: 'cost', bobot: 0.30 },
    { id: 2, kode: 'C2', nama: 'Jarak ke Kampus', type: 'cost', bobot: 0.25 },
    { id: 3, kode: 'C3', nama: 'Fasilitas Kamar', type: 'benefit', bobot: 0.20 },
    { id: 4, kode: 'C4', nama: 'Keamanan', type: 'benefit', bobot: 0.15 },
    { id: 5, kode: 'C5', nama: 'Kebersihan', type: 'benefit', bobot: 0.10 }
];

const defaultAlternatif = [
    { id: 1, nama: 'Kost Ibu Aris Non AC', alamat: 'Pamulang, Tangerang Selatan (Dekat UNPAM Viktor)' },
    { id: 2, nama: 'Kost Hive Living Tipe D', alamat: 'Serpong, Tangerang Selatan' },
    { id: 3, nama: 'Kost Hive Living Tipe E', alamat: 'Serpong, Tangerang Selatan' },
    { id: 4, nama: 'Kost The Best Kost Tipe B', alamat: 'Serpong, Tangerang Selatan' },
    { id: 5, nama: 'Kost The Best Kost Tipe A', alamat: 'Serpong, Tangerang Selatan' },
    { id: 6, nama: 'Kost The Best Kost Tipe C', alamat: 'Serpong, Tangerang Selatan' },
    { id: 7, nama: 'Kost Hive Living Tipe A', alamat: 'Serpong, Tangerang Selatan' },
    { id: 8, nama: 'Kost Saga Bukit Dago', alamat: 'Kec. Setu, Tangerang Selatan' },
    { id: 9, nama: 'Kost Hive Living Tipe C', alamat: 'Serpong, Tangerang Selatan' },
    { id: 10, nama: 'Kost Exclusive Buana Residence', alamat: 'Serpong, Tangerang Selatan' }
];

const defaultPenilaian = [
    { id_alternatif: 1, id_kriteria: 1, nilai: 900000 }, { id_alternatif: 1, id_kriteria: 2, nilai: 300 }, { id_alternatif: 1, id_kriteria: 3, nilai: 2 }, { id_alternatif: 1, id_kriteria: 4, nilai: 3 }, { id_alternatif: 1, id_kriteria: 5, nilai: 3 },
    { id_alternatif: 2, id_kriteria: 1, nilai: 1680000 }, { id_alternatif: 2, id_kriteria: 2, nilai: 2500 }, { id_alternatif: 2, id_kriteria: 3, nilai: 5 }, { id_alternatif: 2, id_kriteria: 4, nilai: 4 }, { id_alternatif: 2, id_kriteria: 5, nilai: 4 },
    { id_alternatif: 3, id_kriteria: 1, nilai: 1490000 }, { id_alternatif: 3, id_kriteria: 2, nilai: 2500 }, { id_alternatif: 3, id_kriteria: 3, nilai: 5 }, { id_alternatif: 3, id_kriteria: 4, nilai: 4 }, { id_alternatif: 3, id_kriteria: 5, nilai: 4 },
    { id_alternatif: 4, id_kriteria: 1, nilai: 1700000 }, { id_alternatif: 4, id_kriteria: 2, nilai: 3000 }, { id_alternatif: 4, id_kriteria: 3, nilai: 5 }, { id_alternatif: 4, id_kriteria: 4, nilai: 4 }, { id_alternatif: 4, id_kriteria: 5, nilai: 4 },
    { id_alternatif: 5, id_kriteria: 1, nilai: 1740000 }, { id_alternatif: 5, id_kriteria: 2, nilai: 3000 }, { id_alternatif: 5, id_kriteria: 3, nilai: 5 }, { id_alternatif: 5, id_kriteria: 4, nilai: 4 }, { id_alternatif: 5, id_kriteria: 5, nilai: 4 },
    { id_alternatif: 6, id_kriteria: 1, nilai: 1600000 }, { id_alternatif: 6, id_kriteria: 2, nilai: 3000 }, { id_alternatif: 6, id_kriteria: 3, nilai: 5 }, { id_alternatif: 6, id_kriteria: 4, nilai: 4 }, { id_alternatif: 6, id_kriteria: 5, nilai: 4 },
    { id_alternatif: 7, id_kriteria: 1, nilai: 2040000 }, { id_alternatif: 7, id_kriteria: 2, nilai: 2500 }, { id_alternatif: 7, id_kriteria: 3, nilai: 5 }, { id_alternatif: 7, id_kriteria: 4, nilai: 5 }, { id_alternatif: 7, id_kriteria: 5, nilai: 5 },
    { id_alternatif: 8, id_kriteria: 1, nilai: 900000 }, { id_alternatif: 8, id_kriteria: 2, nilai: 1200 }, { id_alternatif: 8, id_kriteria: 3, nilai: 4 }, { id_alternatif: 8, id_kriteria: 4, nilai: 3 }, { id_alternatif: 8, id_kriteria: 5, nilai: 3 },
    { id_alternatif: 9, id_kriteria: 1, nilai: 1920000 }, { id_alternatif: 9, id_kriteria: 2, nilai: 2500 }, { id_alternatif: 9, id_kriteria: 3, nilai: 5 }, { id_alternatif: 9, id_kriteria: 4, nilai: 4 }, { id_alternatif: 9, id_kriteria: 5, nilai: 4 },
    { id_alternatif: 10, id_kriteria: 1, nilai: 2000000 }, { id_alternatif: 10, id_kriteria: 2, nilai: 3500 }, { id_alternatif: 10, id_kriteria: 3, nilai: 5 }, { id_alternatif: 10, id_kriteria: 4, nilai: 5 }, { id_alternatif: 10, id_kriteria: 5, nilai: 4 }
];

// LocalStorage Helpers
const DB = {
    getKriteria: () => JSON.parse(localStorage.getItem('kriteria')) || defaultKriteria,
    setKriteria: (data) => localStorage.setItem('kriteria', JSON.stringify(data)),
    
    getAlternatif: () => JSON.parse(localStorage.getItem('alternatif')) || defaultAlternatif,
    setAlternatif: (data) => localStorage.setItem('alternatif', JSON.stringify(data)),
    
    getPenilaian: () => JSON.parse(localStorage.getItem('penilaian')) || defaultPenilaian,
    setPenilaian: (data) => localStorage.setItem('penilaian', JSON.stringify(data)),

    // Initialize with defaults if empty
    init: () => {
        if (!localStorage.getItem('kriteria')) DB.setKriteria(defaultKriteria);
        if (!localStorage.getItem('alternatif')) DB.setAlternatif(defaultAlternatif);
        if (!localStorage.getItem('penilaian')) DB.setPenilaian(defaultPenilaian);
    }
};

DB.init();

// SAW Calculation Logic
function calculateSAW() {
    const kriteria = DB.getKriteria();
    const alternatif = DB.getAlternatif();
    const penilaian = DB.getPenilaian();

    // Map scores to [alt_id][crit_id]
    const matrix_x = {};
    penilaian.forEach(p => {
        if (!matrix_x[p.id_alternatif]) matrix_x[p.id_alternatif] = {};
        matrix_x[p.id_alternatif][p.id_kriteria] = p.nilai;
    });

    // Step 1: Normalization (R)
    const matrix_r = {};
    kriteria.forEach(k => {
        const crit_id = k.id;
        const all_values = Object.values(matrix_x).map(row => row[crit_id]).filter(v => v !== undefined);
        
        if (all_values.length === 0) return;

        const max = Math.max(...all_values);
        const min = Math.min(...all_values);

        alternatif.forEach(a => {
            const alt_id = a.id;
            if (!matrix_r[alt_id]) matrix_r[alt_id] = {};
            const val = matrix_x[alt_id] ? matrix_x[alt_id][crit_id] : 0;

            if (k.type === 'benefit') {
                matrix_r[alt_id][crit_id] = val / max;
            } else {
                matrix_r[alt_id][crit_id] = min / val;
            }
        });
    });

    // Step 2: Preference (V)
    const ranks = alternatif.map(a => {
        const alt_id = a.id;
        let total_v = 0;
        kriteria.forEach(k => {
            const crit_id = k.id;
            const r_val = matrix_r[alt_id] ? (matrix_r[alt_id][crit_id] || 0) : 0;
            total_v += (r_val * k.bobot);
        });
        return { id: alt_id, nama: a.nama, value: total_v };
    });

    // Sort descending
    ranks.sort((a, b) => b.value - a.value);

    return { matrix_x, matrix_r, ranks };
}

// WP Calculation Logic
function calculateWP() {
    const kriteria = DB.getKriteria();
    const alternatif = DB.getAlternatif();
    const penilaian = DB.getPenilaian();

    const total_bobot = kriteria.reduce((sum, k) => sum + k.bobot, 0);
    const kriteria_weights = kriteria.map(k => ({
        ...k,
        w: k.type === 'benefit' ? (k.bobot / total_bobot) : -(k.bobot / total_bobot)
    }));

    // Map scores
    const matrix_x = {};
    penilaian.forEach(p => {
        if (!matrix_x[p.id_alternatif]) matrix_x[p.id_alternatif] = {};
        matrix_x[p.id_alternatif][p.id_kriteria] = p.nilai;
    });

    // Step 1: Calculate S
    const matrix_s = alternatif.map(a => {
        const alt_id = a.id;
        let s_val = 1;
        kriteria_weights.forEach(k => {
            const crit_id = k.id;
            const val = matrix_x[alt_id] ? (matrix_x[alt_id][crit_id] || 0) : 1; // 1 if missing for product
            s_val *= Math.pow(val, k.w);
        });
        return { id: alt_id, nama: a.nama, s: s_val };
    });

    const total_s = matrix_s.reduce((sum, item) => sum + item.s, 0);

    // Step 2: Calculate V
    const matrix_v = matrix_s.map(item => ({
        ...item,
        v: item.s / total_s
    }));

    // Sort descending
    const ranks = [...matrix_v].sort((a, b) => b.v - a.v);

    return { matrix_x, matrix_s, matrix_v, ranks, total_s, kriteria_weights };
}

// SMART Calculation Logic
function calculateSMART() {
    const kriteria = DB.getKriteria();
    const alternatif = DB.getAlternatif();
    const penilaian = DB.getPenilaian();

    // Map scores
    const matrix_x = {};
    penilaian.forEach(p => {
        if (!matrix_x[p.id_alternatif]) matrix_x[p.id_alternatif] = {};
        matrix_x[p.id_alternatif][p.id_kriteria] = p.nilai;
    });

    // Step 1: Normalization of weights
    const total_bobot = kriteria.reduce((sum, k) => sum + k.bobot, 0);
    const kriteria_normalized = kriteria.map(k => ({
        ...k,
        w_norm: k.bobot / total_bobot
    }));

    // Step 2: Calculate Utility (u)
    const matrix_u = {};
    kriteria.forEach(k => {
        const crit_id = k.id;
        const all_values = Object.values(matrix_x).map(row => row[crit_id]).filter(v => v !== undefined);
        if (all_values.length === 0) return;

        const max = Math.max(...all_values);
        const min = Math.min(...all_values);

        alternatif.forEach(a => {
            const alt_id = a.id;
            if (!matrix_u[alt_id]) matrix_u[alt_id] = {};
            const val = matrix_x[alt_id] ? matrix_x[alt_id][crit_id] : 0;

            // Utility Formula
            // Benefit: (Cout - Cmin) / (Cmax - Cmin) * 100
            // Cost: (Cmax - Cout) / (Cmax - Cmin) * 100
            if (max === min) {
                matrix_u[alt_id][crit_id] = 100;
            } else {
                if (k.type === 'benefit') {
                    matrix_u[alt_id][crit_id] = ((val - min) / (max - min)) * 100;
                } else {
                    matrix_u[alt_id][crit_id] = ((max - val) / (max - min)) * 100;
                }
            }
        });
    });

    // Step 3: Final Score (Total Utility)
    const ranks = alternatif.map(a => {
        const alt_id = a.id;
        let total_utility = 0;
        kriteria_normalized.forEach(k => {
            const crit_id = k.id;
            const u_val = matrix_u[alt_id] ? (matrix_u[alt_id][crit_id] || 0) : 0;
            total_utility += (u_val * k.w_norm);
        });
        return { id: alt_id, nama: a.nama, value: total_utility };
    });

    // Sort descending
    ranks.sort((a, b) => b.value - a.value);

    return { matrix_x, kriteria_normalized, matrix_u, ranks };
}
