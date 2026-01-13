import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';
import { DRACOLoader } from 'three/examples/jsm/loaders/DRACOLoader.js';

function initRoom() {
    console.log("initRoom started");
    const canvas = document.getElementById('roomCanvas');
    if (!canvas) {
        console.error("Canvas #roomCanvas not found");
        return;
    }

    const scene = new THREE.Scene();
    scene.background = new THREE.Color("#D9CAD1"); // Couleur du projet original

    const camera = new THREE.PerspectiveCamera(
        35,
        canvas.clientWidth / canvas.clientHeight,
        0.1,
        200
    );

    const renderer = new THREE.WebGLRenderer({ canvas, antialias: true });
    renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.outputColorSpace = THREE.SRGBColorSpace; // Important pour les couleurs

    // Contrôles
    // Contrôles (Configuration EXACTE du site de référence)
    const controls = new OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.05;

    // Limites exacte issues de src/main.js
    controls.minDistance = 1; // MODIFIE : on autorise à se rapprocher plus (était à 5)
    controls.maxDistance = 45;

    controls.minPolarAngle = 0;
    controls.maxPolarAngle = Math.PI / 2;

    controls.minAzimuthAngle = 0;
    controls.maxAzimuthAngle = Math.PI / 2;

    // Lumières
    const light = new THREE.DirectionalLight(0xffffff, 2);
    light.position.set(5, 10, 5);
    scene.add(light);

    const ambientLight = new THREE.AmbientLight(0xffffff, 2); // Lumière plus forte
    scene.add(ambientLight);

    // Loader avec Draco
    const loader = new GLTFLoader();
    const dracoLoader = new DRACOLoader();
    // Point to the draco decoder files (you might need to copy them to public/draco or use a CDN)
    // For now, attempting to use a standard CDN path or local if you have it.
    // Ideally this should be locally hosted.
    dracoLoader.setDecoderPath('https://www.gstatic.com/draco/versioned/decoders/1.5.6/');
    loader.setDRACOLoader(dracoLoader);

    let room = null;
    const raycaster = new THREE.Raycaster();
    const mouse = new THREE.Vector2();
    let hovered = null;

    // Timestamp pour forcer le re-téléchargement du fichier (éviter le cache navigateur)
    const url = '/models/room.glb?v=' + Date.now();

    loader.load(url, (gltf) => {
        console.log("Model loaded successfully");
        room = gltf.scene;
        // CORRECTION ANGLE : 0 degrés (Retour à l'original)
        // Maintenant que la cible (target) est bonne, 0 devrait nous montrer le 'devant' si 180 était le 'derrière'.
        room.rotation.y = 0;

        // 2. Application Scale/Rotation
        const rawBox = new THREE.Box3().setFromObject(room);
        const rawSize = rawBox.getSize(new THREE.Vector3());
        const maxDim = Math.max(rawSize.x, rawSize.y, rawSize.z);

        let scale = 10 / maxDim;
        if (!isFinite(scale)) scale = 1; // Sécurité si box incorrecte
        room.scale.set(scale, scale, scale);

        scene.add(room);

        // IMPORTANT : Force la mise à jour des matrices pour le calcul suivant
        room.updateMatrixWorld(true);

        // 3. Calcul du "Vrai" Centre Final
        const finalBox = new THREE.Box3().setFromObject(room);
        const finalCenter = finalBox.getCenter(new THREE.Vector3());
        const finalSize = finalBox.getSize(new THREE.Vector3());

        console.log("--- DEBUG ---");
        // Liste des objets pour comprendre structure
        room.traverse((child) => {
            if (child.isMesh) console.log("Mesh:", child.name);
        });

        // AIDES VISUELLES RETIREES
        // scene.add(axesHelper);
        // scene.add(boxHelper);

        // --- Fit Camera to Object ---

        // A. On vise le centre réel du modèle
        controls.target.copy(finalCenter);

        // B. Calcul distance caméra
        const visualSize = Math.max(finalSize.x, finalSize.y, finalSize.z);
        const fov = camera.fov * (Math.PI / 180);
        let cameraZ = Math.abs(visualSize / 2 * Math.tan(fov * 2));

        // CORRECTION ZOOM "BOURRIN"
        // Le cadre rouge est ~4x plus grand que la chambre.
        // Donc on divise la distance par 4.5 pour se rapprocher encore plus (demande user)
        cameraZ = cameraZ / 4.5;

        // C. Positionnement caméra
        // On baisse un peu la hauteur (Y) pour "descendre" comme demandé (0.8 -> 0.6)
        const direction = new THREE.Vector3(1, 0.6, 1).normalize();

        // Si finalCenter est NaN (problème modèle), on fallback sur 0,0,0
        const safeCenter = (Number.isNaN(finalCenter.x)) ? new THREE.Vector3(0, 0, 0) : finalCenter;

        const position = safeCenter.clone().add(direction.multiplyScalar(cameraZ));

        camera.position.copy(position);

        controls.update();
        camera.updateProjectionMatrix();

    }, undefined, (error) => {
        console.error('Erreur lors du chargement de room.glb:', error);
    });

    // MAJ taille rendu
    function onResize() {
        const { clientWidth, clientHeight } = canvas;
        camera.aspect = clientWidth / clientHeight || 1;
        camera.updateProjectionMatrix();
        renderer.setSize(clientWidth, clientHeight);
    }
    window.addEventListener('resize', onResize);

    // Souris dans le canvas
    canvas.addEventListener('mousemove', (event) => {
        const rect = canvas.getBoundingClientRect();
        mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
        mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;
    });

    // Clic = navigation
    canvas.addEventListener('click', () => {
        if (!hovered) return;

        let targetName = hovered.name;
        // Remonte à l'objet parent si besoin pour trouver le bon nom
        while (targetName === '' && hovered.parent) {
            hovered = hovered.parent;
            targetName = hovered.name;
        }

        if (targetName === 'Desk_Projects') {
            window.location.href = '/projets';
        } else if (targetName === 'Frame_About') {
            window.location.href = '/a-propos';
        } else if (targetName === 'Phone_Contact') {
            window.location.href = '/contact';
        }
    });

    function animate() {
        requestAnimationFrame(animate);
        controls.update(); // requis si damping activé

        if (room) {
            raycaster.setFromCamera(mouse, camera);
            const intersects = raycaster.intersectObjects(room.children, true);

            if (intersects.length > 0) {
                hovered = intersects[0].object;
                document.body.style.cursor = 'pointer';
            } else {
                hovered = null;
                document.body.style.cursor = 'default';
            }
        }

        renderer.render(scene, camera);
    }
    animate();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initRoom);
} else {
    initRoom();
}
