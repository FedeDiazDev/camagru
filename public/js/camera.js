const video = document.getElementById('video')
const canvas = document.getElementById('canvas')
const ctx = canvas.getContext('2d')
const capturedImage = document.getElementById('capturedImage')
const stickerPreview = document.getElementById('stickerPreview')

const startCameraBtn = document.getElementById('startCameraBtn')
const captureBtn = document.getElementById('captureBtn')
const retakeBtn = document.getElementById('retakeBtn')
const shareBtn = document.getElementById('shareBtn')
const uploadInput = document.getElementById('uploadInput')

const brightnessInput = document.getElementById('brightness')
const contrastInput = document.getElementById('contrast')
const brightnessValue = document.getElementById('brightnessValue')
const contrastValue = document.getElementById('contrastValue')

const stickersContainer = document.getElementById('stickers')
const filtersContainer = document.getElementById('filters')

const imageContainer = document.getElementById('imageContainer');

const titleInput = document.getElementById("photoTitle");
const titleLength = document.getElementById("titleLength");

const darkStickers = [
    { src: "/images/calabaza.png", name: "pumpkin" },
    { src: "/images/fantasma.png", name: "ghost" },
    { src: "/images/ojo.png", name: "eye" },
    { src: "/images/murcielago.png", name: "bat" },
];
const filters = [
    { name: "none", label: "Original" },
    { name: "noir", label: "Noir" },
    { name: "gothic", label: "Gothic" },
    { name: "shadow", label: "Shadow" },
    { name: "mystic", label: "Mystic" }
]

let isCapturing = false
let selectedSticker = null
let selectedFilter = "none"
let brightness = 100
let contrast = 100

let movableStickers = []


titleInput.addEventListener("input", () => {
    titleLength.textContent = `${titleInput.value.length} / 100`;
});

function getFilterStyle() {
    let base = `brightness(${brightness}%) contrast(${contrast}%)`
    switch (selectedFilter) {
        case "noir":
            return `${base} grayscale(100%) contrast(120%)`
        case "gothic":
            return `${base} sepia(30%) hue-rotate(270deg) saturate(150%)`
        case "shadow":
            return `${base} brightness(70%) contrast(130%)`
        case "mystic":
            return `${base} hue-rotate(240deg) saturate(120%)`
        default:
            return base
    }
}

function applyFilter() {
    if (isCapturing) {
        video.style.filter = getFilterStyle()
    } else {
        capturedImage.style.filter = getFilterStyle()
        movableStickers.forEach(s => s.element.style.filter = getFilterStyle())
    }
}

function updateBrightness(value) {
    brightness = value
    brightnessValue.textContent = `${value}%`
    applyFilter()
}

function updateContrast(value) {
    contrast = value
    contrastValue.textContent = `${value}%`
    applyFilter()
}

async function startCamera() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480 } })
        video.srcObject = stream
        isCapturing = true
        startCameraBtn.classList.add('hidden')
        captureBtn.classList.remove('hidden')
        retakeBtn.classList.add('hidden')
        shareBtn.classList.add('hidden')
        capturedImage.classList.add('hidden')
        video.classList.remove('hidden')
        imageContainer.classList.add('hidden')
        clearAllMovableStickers()
        applyFilter()
    } catch (err) {
        alert('Could not access camera. Please check permissions.')
        console.error(err)
    }
}

function stopCamera() {
    if (video.srcObject) {
        video.srcObject.getTracks().forEach(track => track.stop())
        video.srcObject = null
    }
    isCapturing = false
    startCameraBtn.classList.remove('hidden')
    captureBtn.classList.add('hidden')
}

let currentDraggingSticker = null
let offsetX, offsetY

function clearAllMovableStickers() {
    movableStickers.forEach(s => imageContainer.removeChild(s.element))
    movableStickers = []
}

function addMovableSticker(stickerSrc) {
    const sticker = document.createElement('img');
    sticker.src = stickerSrc;
    sticker.className = 'movable-sticker';
    sticker.style.position = 'absolute';
    sticker.style.width = '64px';
    sticker.style.height = '64px';
    sticker.style.cursor = 'move';
    sticker.style.userSelect = 'none';


    const baseWidth = capturedImage.clientWidth || 640;
    const baseHeight = capturedImage.clientHeight || 480;

    sticker.style.left = (baseWidth / 2 - 24) + 'px';
    sticker.style.top = (baseHeight / 2 - 24) + 'px';


    sticker.addEventListener('mousedown', (e) => {
        currentDraggingSticker = sticker
        offsetX = e.clientX - sticker.getBoundingClientRect().left
        offsetY = e.clientY - sticker.getBoundingClientRect().top
        e.preventDefault()
    })

    sticker.addEventListener('dragstart', e => e.preventDefault())

    imageContainer.appendChild(sticker)

    movableStickers.push({ element: sticker, emoji: stickerSrc, x: parseFloat(sticker.style.left), y: parseFloat(sticker.style.top) })
}

window.addEventListener('mouseup', () => {
    currentDraggingSticker = null
})

window.addEventListener('mousemove', (e) => {
    if (!currentDraggingSticker) return

    const imageRect = capturedImage.getBoundingClientRect();
    const containerRect = imageContainer.getBoundingClientRect();

    let clientX = e.clientX - offsetX;
    let clientY = e.clientY - offsetY;

    const minX = imageRect.left;
    const maxX = imageRect.right - currentDraggingSticker.offsetWidth;
    const minY = imageRect.top;
    const maxY = imageRect.bottom - currentDraggingSticker.offsetHeight;

    clientX = Math.max(minX, Math.min(clientX, maxX));
    clientY = Math.max(minY, Math.min(clientY, maxY));

    let left = clientX - containerRect.left;
    let top = clientY - containerRect.top;

    currentDraggingSticker.style.left = left + 'px'
    currentDraggingSticker.style.top = top + 'px'

    const found = movableStickers.find(s => s.element === currentDraggingSticker)
    if (found) {
        found.x = left
        found.y = top
    }
})

function capturePhoto() {
    if (!isCapturing) return;

    const realWidth = video.videoWidth;
    const realHeight = video.videoHeight;

    canvas.width = realWidth;
    canvas.height = realHeight;

    ctx.drawImage(video, 0, 0, realWidth, realHeight);

    const imageData = canvas.toDataURL('image/png');
    capturedImage.src = imageData;
    capturedImage.classList.remove('hidden');
    imageContainer.classList.remove('hidden');
    video.classList.add('hidden');

    captureBtn.classList.add('hidden');
    retakeBtn.classList.remove('hidden');
    shareBtn.classList.remove('hidden');

    stopCamera();
}


function retakePhoto() {
    capturedImage.classList.add('hidden')
    retakeBtn.classList.add('hidden')
    shareBtn.classList.add('hidden')

    clearAllMovableStickers()
    startCamera()
}


function sharePhoto() {
    if (movableStickers.length === 0) {
        showAlert("Por favor, añade al menos un sticker antes de tomar la foto.", "error");
        return;
    }
    if (!titleInput.value) {
        showAlert("Por favor, agrega un título a la imagen.", "error");
        return;
    }
    savePhoto(titleInput.value);
}
function dataURLtoFile(dataurl, filename) {
    let arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n)
    while (n--) {
        u8arr[n] = bstr.charCodeAt(n)
    }
    return new File([u8arr], filename, { type: mime })
}

function handleUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = (e) => {
        capturedImage.src = e.target.result;
        capturedImage.classList.remove('hidden');
        imageContainer.classList.remove('hidden');
        video.classList.add('hidden');

        capturedImage.onload = () => {
            canvas.width = capturedImage.naturalWidth;
            canvas.height = capturedImage.naturalHeight;
            ctx.filter = getFilterStyle();
            ctx.drawImage(capturedImage, 0, 0, canvas.width, canvas.height);
            ctx.filter = 'none';
        };

        clearAllMovableStickers();
        startCameraBtn.classList.remove('hidden');
        captureBtn.classList.add('hidden');
        retakeBtn.classList.remove('hidden');
        shareBtn.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}


function createStickerButtons() {
    stickersContainer.innerHTML = '';
    darkStickers.forEach(sticker => {
        const btn = document.createElement('button');
        btn.className = 'sticker-btn cursor-pointer p-1';
        const img = document.createElement('img');
        img.src = sticker.src;
        img.alt = sticker.name;
        img.className = 'w-10 h-10';
        btn.appendChild(img);

        btn.addEventListener('click', () => {
            addMovableSticker(sticker.src);
        });

        stickersContainer.appendChild(btn);
    });
}


function createFilterButtons() {
    filters.forEach(filter => {
        const btn = document.createElement('button')
        btn.textContent = filter.label
        btn.type = 'button'
        btn.className = 'px-3 py-1 rounded bg-gray-700 hover:bg-purple-700 text-white text-sm'
        btn.addEventListener('click', () => {
            selectedFilter = filter.name
            applyFilter()
            Array.from(filtersContainer.children).forEach(child => {
                child.classList.remove('bg-purple-700')
                child.classList.add('bg-gray-700')
            })
            btn.classList.remove('bg-gray-700')
            btn.classList.add('bg-purple-700')
        })
        filtersContainer.appendChild(btn)
    })
    filtersContainer.firstChild.classList.add('bg-purple-700')
}

function savePhoto(title) {
    const imageData = canvas.toDataURL('image/png');

    const imageRect = capturedImage.getBoundingClientRect();

    const realWidth = canvas.width;
    const realHeight = canvas.height;

    const ratioX = realWidth / imageRect.width;
    const ratioY = realHeight / imageRect.height;

    const stickersData = movableStickers.map(s => {
        const stickerRect = s.element.getBoundingClientRect();

        const relativeX = stickerRect.left - imageRect.left;
        const relativeY = stickerRect.top - imageRect.top;

        return {
            src: s.emoji,
            x: relativeX * ratioX,
            y: relativeY * ratioY,
            width: stickerRect.width * ratioX,
            height: stickerRect.height * ratioY
        };
    });

    fetch('/camera.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            userId: window.USER_ID,
            title: title,
            baseImage: imageData,
            stickers: stickersData,
            filter: selectedFilter,
            brightness: brightness,
            contrast: contrast
        })
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showAlert('Post creado! ID: ' + data.postId, "success");
                setTimeout(() => {
                    window.location.href = "/gallery";
                }, 1000);
            } else {
                alert(data.error);
            }
        })
        .catch(console.error);
}


startCameraBtn.addEventListener('click', startCamera);
captureBtn.addEventListener('click', capturePhoto);
retakeBtn.addEventListener('click', retakePhoto);
shareBtn.addEventListener('click', sharePhoto);
uploadInput.addEventListener('change', handleUpload);

brightnessInput.addEventListener('input', e => updateBrightness(e.target.value));
contrastInput.addEventListener('input', e => updateContrast(e.target.value));

createStickerButtons();
createFilterButtons();
updateBrightness(brightness);
updateContrast(contrast);
