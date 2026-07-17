<section class="hero-pendidikan">

    <div class="container">

        <div class="hero-content">

            <span class="badge-title">
                <i class="fa-solid fa-graduation-cap"></i>
                {{ $badge ?? 'Page Title'}}
            </span>

            <h1>
                {{ $title ?? 'Page Title' }} 
                <span>{{ $titlespan ?? 'Page Title'}}</span>
            </h1>

            <p>
                {{ $subtitle ?? 'Page Title'}}
            </p>

        </div>

    </div>

    <div class="wave">
        <svg viewBox="0 0 1440 320">
            <path fill="#f8fafc" fill-opacity="1"
                d="M0,256L60,240C120,224,240,192,360,176C480,160,600,160,720,176C840,192,960,224,1080,224C1200,224,1320,192,1380,176L1440,160L1440,320L0,320Z">
            </path>
        </svg>
    </div>

</section>