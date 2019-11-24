@switch($name)
    {{--user--}}
    @case("user")
    <div class="dropdown">
        <button class="btn  btn-sm btn-primary dropdown-toggle" type="button" id="{{ $value['id'] }}"
                data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            Action
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="{{ $value['id'] }}">
            <a class="dropdown-item btn-edit" href="{{ route('user.edit', $value['id']) }}">Edit</a>
            <a class="dropdown-item btn-destroy" href="{{ route('user.destroy', $value['id']) }}">Hapus</a>
        </div>
    </div>
    @break
    {{--user--}}
    {{--halaman--}}
    @case("halaman")
    <div class="dropdown">
        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="{{ $value['hal_id'] }}"
                data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            Action
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="{{ $value['hal_id'] }}">
            <a class="dropdown-item btn-edit" href="{{ route('halaman.edit', $value['hal_id']) }}">Edit</a>
            <a class="dropdown-item btn-destroy" href="{{ route('halaman.destroy', $value['hal_id']) }}">Hapus</a>
        </div>
    </div>
    @break
    {{--berita kategori--}}
    @case("bk")
    <div class="dropdown">
        <button class="btn  btn-sm btn-primary dropdown-toggle" type="button" id="{{ $value['bk_id'] }}"
                data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            Action
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="{{ $value['bk_id'] }}">
            <a class="dropdown-item btn-edit" href="{{ route('beritakategori.edit', $value['bk_id']) }}">Edit</a>
            <a class="dropdown-item btn-destroy" href="{{ route('beritakategori.destroy', $value['bk_id']) }}">Hapus</a>
        </div>
    </div>
    @break
    {{--berita kategori--}}

    {{--berita--}}
    @case("berita")
    <div class="dropdown">
        <button class="btn  btn-sm btn-primary dropdown-toggle" type="button" id="{{ $value['berita_id'] }}"
                data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            Action
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="{{ $value['berita_id'] }}">
            <a class="dropdown-item btn-edit" href="{{ route('berita.edit', $value['berita_id']) }}">Edit</a>
            <a class="dropdown-item btn-destroy" href="{{ route('berita.destroy', $value['berita_id']) }}">Hapus</a>
        </div>
    </div>
    @break
    {{--berita--}}

    {{--survey--}}
    @case("survey")
    <div class="dropdown">
        <button class="btn  btn-sm btn-primary dropdown-toggle" type="button" id="{{ $value['survey_id'] }}"
                data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            Action
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="{{ $value['survey_id'] }}">
            <a class="dropdown-item btn-chat" href="{{ $value['survey_id'] }}">Chat</a>
            @if(auth()->user()->role != 'user')
                <a class="dropdown-item btn-edit" href="{{ route('survey.edit', $value['survey_id']) }}">Edit</a>
                <a class="dropdown-item btn-destroy" href="{{ route('survey.destroy', $value['survey_id']) }}">Hapus</a>
            @elseif(auth()->user()->role == 'user' && $value->status->ss_level == 1)
                <a class="dropdown-item btn-edit" href="{{ route('survey.edit', $value['survey_id']) }}">Edit</a>
            @endif
            @if($value->status->ss_level >=  2)
                <a class="dropdown-item btn-excel" href="{{ route('survey.edit', $value['survey_id']) }}">Excel</a>
            @endif
            @if($value->status->ss_level >=  3)
                <a class="dropdown-item btn-pdf" href="{{ route('survey.edit', $value['survey_id']) }}">PDF</a>
            @endif
        </div>
    </div>
    @break
    {{--survey--}}

    {{--anggota--}}
    @case("anggota")
    <div class="dropdown">
        <button class="btn  btn-sm btn-primary dropdown-toggle" type="button" id="{{ $value['id'] }}"
                data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
            Action
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="{{ $value['id'] }}">
            <a class="dropdown-item btn-edit" href="{{ route('anggota.edit', $value['id']) }}">Edit</a>
            <a class="dropdown-item btn-destroy" href="{{ route('anggota.destroy', $value['id']) }}">Hapus</a>
        </div>
    </div>
    @break
    {{--anggota--}}

    {{--penelitiananggota--}}
    @case("penelitiananggota")
    <button class="btn btn-primary btn-select" data-id="{{ $value['id'] }}">
        Tambahkan Anggota Ini
    </button>
    @break
    {{--penelitiananggota--}}

    {{--penelitian--}}
    @case("penelitian")
    @if($value->status->ss_level > 0)
        <div class="dropdown">
            <button class="btn  btn-sm btn-primary dropdown-toggle" type="button" id="{{ $value['penelitian_id'] }}"
                    data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                Action
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="{{ $value['penelitian_id'] }}">

                @if(auth()->user()->role == 'user')
                    <a class="dropdown-item btn-upload"
                       href="{{ route('penelitian.edit', $value['penelitian_id']) }}">Proposal</a>
                    @if($value->status->ss_level == 2 && !isset($is_pengabdian))
                        <button class="dropdown-item btn-anggota" data-id="{{ $value['penelitian_id'] }}">Anggota
                        </button>
                    @endif
                    <a class="dropdown-item btn-edit"
                       href="{{ route('penelitian.edit', $value['penelitian_id']) }}">Edit</a>
                    <a class="dropdown-item btn-destroy"
                       href="{{ route('penelitian.destroy', $value['penelitian_id']) }}">Hapus</a>
                @elseif(auth()->user()->role == 'admin')
                    @if(count($value->files) > 0)
                        <a class="dropdown-item btn-upload"
                           href="{{ route('penelitian.edit', $value['penelitian_id']) }}">Proposal</a>
                        <a class="dropdown-item btn-status"
                           href="{{ route('penelitian.edit', $value['penelitian_id']) }}">Ubah Status</a>
                    @else
                        <a class="dropdown-item">File belum diupload</a>
                    @endif
                @endif
            </div>
        </div>
    @else
        <span class="badge badge-danger p-2">Penelitian Ditolak</span>
    @endif
    @break
    {{--penelitian--}}

    {{--pengabdian--}}
    @case("pengabdian")
    @if($value->status->ss_level > 0)
        <div class="dropdown">
            <button class="btn  btn-sm btn-primary dropdown-toggle" type="button" id="{{ $value['penelitian_id'] }}"
                    data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                Action
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="{{ $value['penelitian_id'] }}">

                @if(auth()->user()->role == 'user')
                    <a class="dropdown-item btn-upload"
                       href="{{ route('pengabdian.edit', $value['penelitian_id']) }}">Proposal</a>
                    @if($value->status->ss_level == 2 && !isset($is_pengabdian))
                        <button class="dropdown-item btn-anggota" data-id="{{ $value['penelitian_id'] }}">Anggota
                        </button>
                    @endif
                    <a class="dropdown-item btn-edit"
                       href="{{ route('pengabdian.edit', $value['penelitian_id']) }}">Edit</a>
                    <a class="dropdown-item btn-destroy"
                       href="{{ route('pengabdian.destroy', $value['penelitian_id']) }}">Hapus</a>
                @elseif(auth()->user()->role == 'admin')
                    @if(count($value->files) > 0)
                        <a class="dropdown-item btn-upload"
                           href="{{ route('pengabdian.edit', $value['penelitian_id']) }}">Proposal</a>
                        <a class="dropdown-item btn-status"
                           href="{{ route('pengabdian.edit', $value['penelitian_id']) }}">Ubah Status</a>
                    @else
                        <a class="dropdown-item">File belum diupload</a>
                    @endif
                @endif
            </div>
        </div>
    @else
        <span class="badge badge-danger p-2">Penelitian Ditolak</span>
    @endif
    @break
    {{--pengabdian--}}

    @default
    <strong>wrong action name</strong>
@endswitch
