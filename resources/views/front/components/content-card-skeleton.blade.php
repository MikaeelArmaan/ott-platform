<style>

/* ===== OTT Skeleton Loader ===== */

.skeleton-card {
    width: 100%;
}

/* Poster placeholder */

.skeleton-poster {
    position: relative;
    height: 240px;
    border-radius: 10px;
    background: #18181b;
    overflow: hidden;
}

/* Shimmer layer */

.skeleton-poster::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(239,68,68,0.25),
        transparent
    );
    transform: translateX(-100%);
    animation: skeleton-shimmer 1.6s infinite;
}

/* Title placeholder */

.skeleton-title {
    height: 10px;
    width: 70%;
    border-radius: 4px;
    background: #27272a;
}

/* Subtitle placeholder */

.skeleton-sub {
    height: 8px;
    width: 40%;
    border-radius: 4px;
    background: #27272a;
}

/* Animation */

@keyframes skeleton-shimmer {
    100% {
        transform: translateX(100%);
    }
}

</style>


<div class="skeleton-card">

    {{-- Poster --}}
    <div class="skeleton-poster"></div>

    {{-- Text --}}
    <div class="mt-2 space-y-2">
        <div class="skeleton-title"></div>
        <div class="skeleton-sub"></div>
    </div>

</div>