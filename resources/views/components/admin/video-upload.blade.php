<div class="relative">

    <!-- Drop Zone -->
    <div
        id="{{ $name }}Drop"
        class="border-2 border-dashed border-zinc-700 rounded
               p-5 text-center cursor-pointer
               hover:border-red-500 transition"
        onclick="document.getElementById('{{ $name }}').click()"
        ondragover="dragOver(event)"
        ondragleave="dragLeave(event,this)"
        ondrop="handleDrop(event,'{{ $name }}')"
    >
        <p class="text-gray-300 text-sm">{{ $label }}</p>
        <p class="text-xs text-gray-500">MP4 / WEBM / OGG</p>
    </div>

    <!-- Hidden File Input -->
    <input
        type="file"
        name="{{ $name }}"
        id="{{ $name }}"
        accept="video/mp4,video/webm,video/ogg"
        class="hidden"
        onchange="previewVideo(event,'{{ $name }}Preview','{{ $name }}Filename','{{ $name }}Progress')"
    >

    <!-- Filename -->
    <p id="{{ $name }}Filename"
       class="text-xs text-gray-400 mt-2"></p>

    <!-- Progress Bar -->
    <div class="w-full bg-zinc-800 rounded h-2 mt-2 hidden"
         id="{{ $name }}ProgressWrap">
        <div id="{{ $name }}Progress"
             class="bg-red-600 h-2 rounded w-0"></div>
    </div>

    <!-- Video Preview -->
    <video
        id="{{ $name }}Preview"
        class="mt-3 w-full max-h-48 rounded hidden"
        controls
    ></video>

</div>
<script>
function dragOver(e){
    e.preventDefault();
}

function dragLeave(e, el){
    el.classList.remove('border-red-500');
}

function handleDrop(e, inputId){
    e.preventDefault();
    const input = document.getElementById(inputId);
    input.files = e.dataTransfer.files;
    input.dispatchEvent(new Event('change'));
}

function previewVideo(event, previewId, filenameId, progressId) {

    const file = event.target.files[0];
    if(!file) return;

    // Size check (500MB)
    if(file.size > 524288000){
        alert("Video too large (max 500MB)");
        event.target.value = "";
        return;
    }

    document.getElementById(filenameId).innerText = file.name;

    // Preview
    const video = document.getElementById(previewId);
    video.src = URL.createObjectURL(file);
    video.classList.remove('hidden');

    // Fake progress animation
    const wrap = document.getElementById(progressId).parentElement;
    const bar  = document.getElementById(progressId);
    wrap.classList.remove('hidden');

    let p = 0;
    const timer = setInterval(()=>{
        p += 10;
        bar.style.width = p + "%";
        if(p >= 100) clearInterval(timer);
    },100);
}
</script>