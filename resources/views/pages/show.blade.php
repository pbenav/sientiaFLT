@extends($page->layout ? (str_contains($page->layout, '.') ? $page->layout : 'layouts.' . $page->layout) : 'layouts.app')

@section('title', $page->meta_title ?? $page->title)

@section('description', $page->meta_description ?? $page->excerpt)

@section('content')

@if($page->template === 'inicio')
    {{-- Inicio page uses the welcome.blade.php content --}}
    @include('pages.inicio')
@elseif(!empty(trim($page->content)))
    {{-- Page has its own content with embedded header --}}
    <section class="ex-section" style="background: #fff;">
        <div class="container-ex">
            <div class="prose prose-lg max-w-none" style="color: #4C586C; line-height: 1.8;">
                {!! $page->content !!}
            </div>
        </div>
    </section>
@else
    {{-- Page without content --}}
    <section class="ex-page-header" style="background: linear-gradient(135deg, #161829 0%, #292D45 100%); padding: 60px 0; color: #fff; text-align: center;">
        <div class="container-ex">
            <h1 style="font-family:'Space Grotesk',sans-serif; font-size: 2.5rem; font-weight: 700; color: #fff; margin-bottom: 10px;">
                {{ $page->title }}
            </h1>
            @if($page->excerpt)
            <p style="color: #C9C6C6; font-size: 1.1rem; max-width: 600px; margin: 0 auto;">{{ $page->excerpt }}</p>
            @endif
        </div>
    </section>
    <section class="ex-section" style="background: #fff; padding: 60px 0;">
        <div class="container-ex text-center">
            <p style="color: #4C586C; font-size: 1.1rem;">Página en construcción</p>
        </div>
    </section>
@endif

@endsection
