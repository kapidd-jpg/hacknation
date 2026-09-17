// PintarKuy - Room: helper render materi (player + info live), dipakai halaman siswa & guru
export const TIPE_LABEL = {
    video: 'Video Pembelajaran',
    teks: 'Ringkasan Teks',
    video_teks: 'Video + Ringkasan',
};

export const tipeLabel = (t) => TIPE_LABEL[t] || t || 'Video Pembelajaran';

export function youtubeEmbed(url) {
    if (!url) return '';
    const m = String(url).match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/|live\/)|youtu\.be\/)([\w-]{6,})/);
    return m ? 'https://www.youtube.com/embed/' + m[1] : String(url);
}

export function youtubeIdOf(url) {
    const m = String(url || '').match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/|live\/)|youtu\.be\/)([\w-]{6,})/);
    return m ? m[1] : null;
}

export function esc(s) {
    return String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

export const initialsOf = (name) => {
    const parts = String(name || 'Peserta').trim().replace(/\s+/g, ' ').split(' ');
    const first = (parts[0] || '').charAt(0).toUpperCase();
    const second = parts.length > 1 ? parts[1].charAt(0).toUpperCase() : '';
    return (first + second) || 'P';
};

const playerEmpty = (ico, strong, sub) =>
    '<div class="room-player-empty"><span style="font-size:30px;">' + ico + '</span><strong>' + esc(strong) + '</strong><span>' + esc(sub) + '</span></div>';

export function renderMateri({ playerEl, infoEl, kelas, materi, halaman, pengirim, preserve }) {
    if (!playerEl || !infoEl) return;

    const playerKept = preserve && playerEl.querySelector('iframe');

    if (!materi) {
        playerEl.innerHTML = playerEmpty('🎓', 'Menunggu tutor memulai materi', 'Belum ada materi yang dibawakan di room ini.');
        infoEl.innerHTML =
            '<p class="room-materi-kicker">Menunggu tutor</p>' +
            '<h2 class="room-materi-title">Belum ada materi</h2>' +
            '<p class="room-konten"><em class="room-konten-empty">Tunggu sampai guru memilih materi di ruang ini.</em></p>';
        return;
    }

    const tipe = materi.tipe || 'video';
    const namaKelas = typeof kelas === 'string' ? kelas : (kelas && kelas.name) ? kelas.name : '';

    if (tipe !== 'teks' && materi.video_url && !playerKept) {
        playerEl.innerHTML = youtubeIdOf(materi.video_url)
            ? '<div class="room-player-yt" id="pkYtHost"></div>'
            : '<iframe class="room-player-frame" src="' + esc(youtubeEmbed(materi.video_url)) + '"' +
              ' title="Video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
    } else if (tipe !== 'teks' && !playerKept) {
        playerEl.innerHTML = playerEmpty('🎬', 'Video sedang disiapkan tutor', 'Tutor akan mulai menayangkan sebentar lagi.');
    } else {
        playerEl.innerHTML = playerEmpty('📄', 'Ringkasan Teks', 'Ringkasan modul ini tampil di bawah.');
    }

    const konten = materi.konten
        ? esc(materi.konten)
        : (tipe === 'teks'
            ? '<em class="room-konten-empty">Belum ada ringkasan untuk modul ini.</em>'
            : '<em class="room-konten-empty">Video sedang ditayangkan oleh tutor.</em>');

    let out = '<p class="room-materi-kicker">' + esc(tipeLabel(tipe)) +
        ' • Dibawakan oleh <span class="lb-code">' + esc(pengirim || 'Tutor') + '</span>';
    if (halaman) out += ' • Halaman <strong>' + parseInt(halaman, 10) + '</strong>';
    out += '</p>';
    out += '<h2 class="room-materi-title">' + esc(materi.judul || 'Materi') + '</h2>';
    if (namaKelas) out += '<p class="room-materi-kelas">' + esc(namaKelas) + '</p>';
    out += '<p class="room-konten">' + konten + '</p>';
    infoEl.innerHTML = out;
}