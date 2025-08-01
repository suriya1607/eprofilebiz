<div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16" @if (checkFrontLanguageSession() == 'ar') dir="rtl" @endif>
        @foreach ($blogs as $blog)
            <div
                class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                <a href="{{ route('fornt-blog-show', $blog->slug) }}" class="block">
                    <div class="aspect-w-16 aspect-h-9 relative">
                        <a href="{{ route('fornt-blog-show', $blog->slug) }}">
                            <div class="blog-img">
                                <img src="{{ isset($blog->blog_image) ? $blog->blog_image : asset('front/images/about-1.png') }}"
                                    alt="Blog post" class="h-[230px] w-100 object-fit-cover" />
                            </div>
                        </a>
                    </div>
                </a>
                <div class="p-6">
                    <div class="flex items-center space-x-2 mb-3">
                        {{-- <span
                            class="bg-primary-100 text-primary-700 text-xs font-medium px-3 py-1 rounded-full">Business</span> --}}
                    </div>
                    @php
                        $description = $blog->description;
                        $wordCount = str_word_count(strip_tags($description));
                    @endphp

                    <a href="{{ route('fornt-blog-show', $blog->slug) }}">
                        <h3 class="text-xl font-bold text-secondary-800 mb-3 hover:text-primary-600 transition-colors">
                            {{ $blog->title }}</h3>
                    </a>
                    @if ($wordCount > 35)
                        <p class="text-secondary-600 mb-4 line-clamp-3">
                            {!! str_replace('&nbsp;', ' ', \Illuminate\Support\Str::words(strip_tags($description), 35, '...')) !!}
                        </p>
                    @else
                        <p class="text-secondary-600 mb-4 line-clamp-3">{!! str_replace('&nbsp;', ' ', $description) !!}</p>
                    @endif
                    <div class="text-secondary-800 fs-18 date-time mt-2">
                        <span><i class="fas fa-calendar-alt icon-color-bs-purple"></i><span
                                class="ms-2">{{ $blog->created_at->format('F d, Y') }}</span></span> <span
                            class="text-primary ms-4 me-4">|</span>
                        <span> <i class="fas fa-clock icon-color-bs-purple"></i> <span
                                class="ms-2">{{ $blog->created_at->diffForHumans() }} </span> </span>
                    </div>
                    {{-- <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <img src="https://randomuser.me/api/portraits/women/42.jpg" alt="Author"
                                class="w-8 h-8 rounded-full" />
                            <span class="text-sm text-secondary-800">Emily Parker</span>
                        </div>
                        <span class="text-xs text-secondary-500">March 28, 2023</span>
                    </div> --}}
                </div>
            </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $blogs->links() }}
    </div>
</div>
