@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
<div class="admin__content">
    <div class="admin__heading">
        <h2>Admin</h2>
    </div>

    {{-- 検索フォーム --}}
    <form class="admin-search" method="GET" action="{{ route('admin.index') }}">
        <div class="admin-search__row">
            <div class="admin-search__group">
                <label>お名前</label>
                <input class="admin-search__input" type="text" name="name" value="{{ request('name') }}">
            </div>

            <div class="admin-search__group">
                <label>メール</label>
                <input class="admin-search__input" type="text" name="email" value="{{ request('email') }}">
            </div>

            <div class="admin-search__group">
                <label>性別</label>
                <select class="admin-search__select" name="gender">
                    <option value="">全て</option>
                    <option value="1" {{ request('gender') == '1' ? 'selected' : '' }}>男性</option>
                    <option value="2" {{ request('gender') == '2' ? 'selected' : '' }}>女性</option>
                    <option value="3" {{ request('gender') == '3' ? 'selected' : '' }}>その他</option>
                </select>
            </div>

            <div class="admin-search__group">
                <label>お問い合わせの種類</label>
                <select class="admin-search__select" name="category_id">
                    <option value="">全て</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category_id') == (string) $category->id ? 'selected' : '' }}>
                            {{ $category->content }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="admin-search__group">
                <label>日付</label>
                <div class="admin-search__dates">
                    <input class="admin-search__input" type="date" name="date_from" value="{{ request('date_from') }}">
                    <span>〜</span>
                    <input class="admin-search__input" type="date" name="date_to" value="{{ request('date_to') }}">
                </div>
            </div>

            <div class="admin-search__buttons">
                <button class="admin-button admin-button--primary" type="submit">検索</button>
                <a class="admin-button" href="{{ route('admin.index') }}">リセット</a>
                <a class="admin-button" href="{{ route('admin.export', request()->query()) }}">エクスポート</a>
            </div>
        </div>
    </form>

    {{-- 一覧 --}}
    <div class="admin-table__wrap">
        <table class="admin-table">
            <tr>
                <th>お名前</th>
                <th>性別</th>
                <th>メール</th>
                <th>お問い合わせの種類</th>
                <th>日付</th>
                <th>詳細</th>
            </tr>

            @foreach ($contacts as $contact)
                <tr>
                    <td>{{ $contact->first_name }} {{ $contact->last_name }}</td>
                    <td>
                        @if ($contact->gender == 1) 男性
                        @elseif ($contact->gender == 2) 女性
                        @else その他
                        @endif
                    </td>
                    <td>{{ $contact->email }}</td>
                    <td>{{ optional($contact->category)->content }}</td>
                    <td>{{ $contact->created_at->format('Y-m-d') }}</td>
                    <td>
                        <button
                            type="button"
                            class="admin-button js-open-modal"
                            data-delete-url="{{ route('admin.contacts.destroy', $contact) }}"
                            data-name="{{ $contact->first_name }} {{ $contact->last_name }}"
                            data-gender="@if($contact->gender==1)男性@elseif($contact->gender==2)女性@elseその他@endif"
                            data-email="{{ $contact->email }}"
                            data-tel="{{ $contact->tel }}"
                            data-address="{{ $contact->address }}"
                            data-building="{{ $contact->building ?? '' }}"
                            data-category="{{ optional($contact->category)->content }}"
                            data-detail="{{ e($contact->detail) }}"
                            data-created_at="{{ $contact->created_at }}"
                        >
                            詳細
                        </button>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    {{-- ページネーション --}}
    <div class="admin-pagination">
        {{ $contacts->links() }}
    </div>
</div>

{{-- ===== モーダル（1つだけ置く）===== --}}
<div id="detailModalOverlay" class="admin-modal__overlay">
    <div id="detailModal" class="admin-modal">
        <button type="button" id="closeModalBtn" class="admin-modal__close">×</button>

        <div class="admin-modal__head">
            <h3 class="admin-modal__title">お問い合わせ詳細</h3>

            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button class="admin-button admin-button--danger" type="submit" onclick="return confirm('削除しますか？');">
                    削除
                </button>
            </form>
        </div>

        <table class="admin-modal__table">
            <tr><th>お名前</th><td id="m_name"></td></tr>
            <tr><th>性別</th><td id="m_gender"></td></tr>
            <tr><th>メール</th><td id="m_email"></td></tr>
            <tr><th>電話番号</th><td id="m_tel"></td></tr>
            <tr><th>住所</th><td id="m_address"></td></tr>
            <tr><th>建物名</th><td id="m_building"></td></tr>
            <tr><th>お問い合わせの種類</th><td id="m_category"></td></tr>
            <tr><th>お問い合わせ内容</th><td id="m_detail"></td></tr>
            <tr><th>日付</th><td id="m_created_at"></td></tr>
        </table>
    </div>
</div>

<script>
(function () {
    const overlay = document.getElementById('detailModalOverlay');
    const closeBtn = document.getElementById('closeModalBtn');

    const setText = (id, value) => {
        const el = document.getElementById(id);
        if (!el) return;
        el.textContent = value ?? '';
    };

    const openModal = (btn) => {
        setText('m_name', btn.dataset.name);
        setText('m_gender', btn.dataset.gender);
        setText('m_email', btn.dataset.email);
        setText('m_tel', btn.dataset.tel);
        setText('m_address', btn.dataset.address);
        setText('m_building', btn.dataset.building);
        setText('m_category', btn.dataset.category);
        setText('m_detail', btn.dataset.detail);
        setText('m_created_at', btn.dataset.created_at);

        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = btn.dataset.deleteUrl;

        overlay.style.display = 'block';
    };

    const closeModal = () => {
        overlay.style.display = 'none';
    };

    document.querySelectorAll('.js-open-modal').forEach(btn => {
        btn.addEventListener('click', () => openModal(btn));
    });

    closeBtn.addEventListener('click', closeModal);

    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) closeModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && overlay.style.display === 'block') {
            closeModal();
        }
    });
})();
</script>
@endsection
