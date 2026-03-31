@include('layouts.side-dashboard')

<style>
  @keyframes fadeInUp { 0% { opacity: 0; transform: translateY(18px); } 100% { opacity: 1; transform: translateY(0); } }
  .animate-fadeInUp { animation: fadeInUp 0.45s ease-out forwards; }
  .collapse-toggle { cursor: pointer; }
</style>

<section class="bg-gray-50 min-h-screen py-12 px-6">
  <div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <p class="text-sm text-gray-600">Grouped Menu Ingredients • Inventory</p>
      </div>
      <div class="flex items-center gap-3">
        <button onclick="openAddModal()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-full shadow">+ Add Material</button>
       
      </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">

      <!-- Inventory (left, spans 2) -->
      <div class="lg:col-span-2 bg-white rounded-2xl shadow-md p-6 animate-fadeInUp">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Raw Materials</h2>
        <div class="overflow-x-auto rounded-lg border border-gray-100 mb-4">
          <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600">
              <tr>
                <th class="px-4 py-3">Material</th>
                <th class="px-4 py-3 w-28">Qty</th>
                <th class="px-4 py-3 w-20">Unit</th>
                <th class="px-4 py-3 w-28">Price (₱)</th>
                <th class="px-4 py-3 w-36">Per Serve</th>
                <th class="px-4 py-3 w-28">Servings</th>
                <th class="px-4 py-3 w-44 text-center">Actions</th>
              </tr>
            </thead>
            <tbody id="inventoryTable" class="divide-y"></tbody>
          </table>
        </div>

        <!-- Collapsible menu groups -->
        <h3 class="text-lg font-semibold mb-2">Menus — Ingredients (grouped)</h3>
        <div id="menuGroups" class="space-y-3 max-h-96 overflow-auto pr-2">
          <!-- JS populates collapsible menu groups here -->
        </div>
      </div>

      <!-- Right: Menu Overview + totals -->
      <aside class="bg-white rounded-2xl shadow-md p-6 animate-fadeInUp">
        <h3 class="text-lg font-bold text-gray-800 mb-2">Menu Servings Overview</h3>
        <p class="text-xs text-gray-500 mb-4">Card view updates live when inventory changes. Click an ingredient in a group to jump to inventory row.</p>

        <div id="menuCards" class="space-y-3 mb-4"></div>

        <div class="pt-3 border-t">
          <p id="totalMaterials" class="text-sm text-gray-600">Materials: —</p>
          <p id="totalServings" class="text-sm text-gray-600">Sum servings (all mats): —</p>
        </div>
      </aside>
    </div>

    <!-- Menu Servings Table (full width below) -->
    <div class="mt-8 bg-white rounded-2xl shadow-md p-6 animate-fadeInUp">
      <h3 class="text-lg font-bold text-gray-800 mb-4">Menu Servings — Table View</h3>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left">
          <thead class="bg-gray-50 text-gray-600">
            <tr>
              <th class="px-4 py-3">Menu Item</th>
              <th class="px-4 py-3">Ingredients</th>
              <th class="px-4 py-3 w-36">Servings Available</th>
              <th class="px-4 py-3 w-36">Estimated Cost / Serving (₱)</th>
              <th class="px-4 py-3 w-36">Status</th>
            </tr>
          </thead>
          <tbody id="menuTable" class="divide-y"></tbody>
        </table>
      </div>
    </div>

  </div>
</section>

<!-- Add/Edit Modal -->
<div id="materialModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
  <div class="bg-white rounded-2xl shadow-lg w-full max-w-xl p-6 relative">
    <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
    <h3 id="modalTitle" class="text-2xl font-bold text-red-600 mb-3">Add Material</h3>
    <form id="materialForm" class="space-y-3">
      <input type="hidden" id="editIndex" value="">
      <div>
        <label class="text-sm text-gray-700">Material Name</label>
        <input id="matName" class="w-full border rounded-lg p-2" placeholder="e.g. Rice" required>
      </div>
      <div class="grid grid-cols-4 gap-3">
        <div>
          <label class="text-sm text-gray-700">Unit</label>
          <input id="matUnit" class="w-full border rounded-lg p-2" placeholder="kg / pcs / g / L" required>
        </div>
        <div>
          <label class="text-sm text-gray-700">Qty</label>
          <input id="matQty" type="number" step="any" min="0" class="w-full border rounded-lg p-2" required>
        </div>
        <div>
          <label class="text-sm text-gray-700">Per Serving</label>
          <input id="matPerServe" type="number" step="any" min="0" class="w-full border rounded-lg p-2" placeholder="amount used per serving" required>
        </div>
        <div>
          <label class="text-sm text-gray-700">Price (₱)</label>
          <input id="matPrice" type="number" step="any" min="0" class="w-full border rounded-lg p-2" required>
        </div>
      </div>
      <div>
        <label class="text-sm text-gray-700">Optional: Max reference (for progress bar)</label>
        <input id="matMaxRef" type="number" step="1" min="1" class="w-full border rounded-lg p-2" placeholder="e.g. typical full stock (50)">
      </div>
      <div class="flex justify-end gap-3 pt-2">
        <button type="button" onclick="closeModal()" class="bg-gray-200 px-4 py-2 rounded-full">Cancel</button>
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-full">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
/* ----------------------------
   Full integrated frontend-only inventory
   - grouped per menu (collapsible)
   - all menus included (Pork Tonkatsu, Fried Chicken, ... + the 7 you requested)
   - persists to localStorage
   -----------------------------*/

const STORAGE_KEY = 'eatshed_inventory_full_v1';

/* DEFAULT MATERIALS: include every ingredient used by the menus */
const DEFAULT_MATERIALS = [
  // Common & per-menu raw materials (realistic dummy stocks & prices)
  { name: "Rice", unit: "kg", quantity: 25, perServe: 0.25, price: 48, maxRef: 50 },

  // Chicken Pastil items
  { name: "Chicken breast", unit: "kg", quantity: 15, perServe: 0.15, price: 240, maxRef: 30 },
  { name: "Cooking oil", unit: "L", quantity: 12, perServe: 0.02, price: 120, maxRef: 20 },
  { name: "Soy sauce", unit: "L", quantity: 5, perServe: 0.01, price: 90, maxRef: 10 },
  { name: "Garlic", unit: "kg", quantity: 10, perServe: 0.01, price: 160, maxRef: 20 },
  { name: "Onion", unit: "kg", quantity: 10, perServe: 0.01, price: 90, maxRef: 20 },
  { name: "Egg", unit: "pcs", quantity: 200, perServe: 1, price: 9, maxRef: 300 },
  { name: "Calamansi", unit: "pcs", quantity: 100, perServe: 1, price: 5, maxRef: 200 },
  { name: "Garlic bits", unit: "g", quantity: 5000, perServe: 5, price: 0.05, maxRef: 10000 },

  // Chicken Poppers
  { name: "Flour", unit: "kg", quantity: 20, perServe: 0.05, price: 60, maxRef: 40 },
  { name: "Cornstarch", unit: "kg", quantity: 5, perServe: 0.02, price: 70, maxRef: 20 },
  { name: "Seasoning mix", unit: "kg", quantity: 2, perServe: 0.01, price: 500, maxRef: 5 },

  // Hotdog
  { name: "Hotdog", unit: "pcs", quantity: 100, perServe: 2, price: 12, maxRef: 200 },

  // Skinless longganisa
  { name: "Skinless longganisa", unit: "pcs", quantity: 60, perServe: 3, price: 20, maxRef: 100 },

  // Maling
  { name: "Maling (luncheon meat)", unit: "can", quantity: 20, perServe: 0.25, price: 120, maxRef: 40 },

  // Pork Tapa
  { name: "Pork tapa", unit: "kg", quantity: 8, perServe: 0.20, price: 300, maxRef: 20 },

  // Bangus
  { name: "Bangus (milkfish)", unit: "kg", quantity: 6, perServe: 0.25, price: 260, maxRef: 12 },

  // Tonkatsu & fried items
  { name: "Pork cutlet", unit: "kg", quantity: 10, perServe: 0.18, price: 320, maxRef: 20 },
  { name: "Breadcrumbs", unit: "kg", quantity: 5, perServe: 0.04, price: 140, maxRef: 10 },
  { name: "Tonkatsu sauce", unit: "L", quantity: 2, perServe: 0.02, price: 95, maxRef: 5 },
  { name: "Cabbage", unit: "kg", quantity: 8, perServe: 0.04, price: 80, maxRef: 15 },

  // Fried Chicken & related
  { name: "Chicken leg/thigh", unit: "kg", quantity: 20, perServe: 0.25, price: 200, maxRef: 30 },
  { name: "Cornstarch (fried)", unit: "kg", quantity: 3, perServe: 0.01, price: 70, maxRef: 10 },

  // Chicken Ala King
  { name: "Butter", unit: "kg", quantity: 5, perServe: 0.015, price: 130, maxRef: 10 },
  { name: "All-purpose cream", unit: "L", quantity: 5, perServe: 0.05, price: 60, maxRef: 10 },
  { name: "Mushroom", unit: "kg", quantity: 5, perServe: 0.02, price: 70, maxRef: 10 },
  { name: "Carrots", unit: "kg", quantity: 10, perServe: 0.02, price: 80, maxRef: 20 },
  { name: "Bell pepper", unit: "kg", quantity: 5, perServe: 0.015, price: 180, maxRef: 10 },

  // Beef Burger Steak
  { name: "Ground beef", unit: "kg", quantity: 15, perServe: 0.20, price: 360, maxRef: 30 },
  { name: "Onion (beef)", unit: "kg", quantity: 10, perServe: 0.01, price: 90, maxRef: 20 },
  { name: "Gravy", unit: "g", quantity: 5000, perServe: 40, price: 0.05, maxRef: 10000 },

  // Fish Fillet (white sauce)
  { name: "Cream dory fillet", unit: "kg", quantity: 12, perServe: 0.20, price: 260, maxRef: 20 },

  // Pork BBQ
  { name: "Pork (shoulder/belly)", unit: "kg", quantity: 12, perServe: 0.18, price: 300, maxRef: 20 },
  { name: "Soy sauce (bbq)", unit: "L", quantity: 10, perServe: 0.02, price: 90, maxRef: 20 },
  { name: "Brown sugar", unit: "kg", quantity: 10, perServe: 0.015, price: 60, maxRef: 20 }
];

/* RECIPES: per-serving usage */
const RECIPES = {
  // earlier 7 plus the additional menus
  "Chicken Pastil": {
    "Chicken breast": 0.15,
    "Cooking oil": 0.02,
    "Soy sauce": 0.01,
    "Garlic": 0.01,
    "Onion": 0.01,
    "Rice": 0.25,
    "Egg": 1,
    "Calamansi": 1,
    "Garlic bits": 5 // grams
  },

  "Chicken Poppers": {
    "Chicken breast": 0.20,
    "Flour": 0.05,
    "Cornstarch": 0.02,
    "Egg": 1,
    "Seasoning mix": 0.01,
    "Cooking oil": 0.10,
    "Rice": 0.25
  },

  "Hotdog with Rice and Egg": {
    "Hotdog": 2,
    "Cooking oil": 0.02,
    "Rice": 0.25,
    "Egg": 1
  },

  "Skinless Longganisa with Rice and Egg": {
    "Skinless longganisa": 3,
    "Cooking oil": 0.02,
    "Rice": 0.25,
    "Egg": 1
  },

  "Maling with Rice and Egg": {
    "Maling (luncheon meat)": 0.25, // cans
    "Cooking oil": 0.02,
    "Rice": 0.25,
    "Egg": 1
  },

  "Pork Tapa with Rice and Egg": {
    "Pork tapa": 0.20,
    "Cooking oil": 0.02,
    "Rice": 0.25,
    "Egg": 1,
    "Garlic bits": 5
  },

  "Bangus with Rice and Egg": {
    "Bangus (milkfish)": 0.25,
    "Cooking oil": 0.05,
    "Rice": 0.25,
    "Egg": 1
  },

  // Earlier menus from previous conversation:
  "Pork Tonkatsu": {
    "Pork cutlet": 0.18,
    "Egg": 1,
    "Flour": 0.03,
    "Breadcrumbs": 0.04,
    "Tonkatsu sauce": 0.025,
    "Cabbage": 0.04,
    "Cooking oil": 0.05,
    "Rice": 0.25
  },

  "Fried Chicken": {
    "Chicken leg/thigh": 0.25,
    "Flour": 0.04,
    "Cornstarch (fried)": 0.01,
    "Egg": 1,
    "Seasoning mix": 0.01,
    "Cooking oil": 0.05,
    "Rice": 0.25
  },

  "Chicken Ala King": {
    "Chicken breast": 0.20,
    "Butter": 0.015,
    "All-purpose cream": 0.05,
    "Milk": 0.03,
    "Flour": 0.01,
    "Carrots": 0.02,
    "Bell pepper": 0.015,
    "Mushroom": 0.02,
    "Rice": 0.25
  },

  "Beef Burger Steak": {
    "Ground beef": 0.20,
    "Onion (beef)": 0.01,
    "Breadcrumbs": 0.015,
    "Egg": 1,
    "Gravy": 40, // grams
    "Cooking oil": 0.02,
    "Rice": 0.25
  },

  "Fish Fillet in White Sauce": {
    "Cream dory fillet": 0.20,
    "Flour": 0.05,
    "Egg": 1,
    "Butter": 0.015,
    "All-purpose cream": 0.05,
    "Cooking oil": 0.08,
    "Rice": 0.25
  },

  "Pork BBQ": {
    "Pork (shoulder/belly)": 0.18,
    "Soy sauce (bbq)": 0.02,
    "Vinegar": 0.01,
    "Brown sugar": 0.02,
    "Cooking oil": 0.01,
    "Rice": 0.25
  }
};

/* helpers: load/save */
function loadMaterials() {
  const raw = localStorage.getItem(STORAGE_KEY);
  if (!raw) return JSON.parse(JSON.stringify(DEFAULT_MATERIALS));
  try {
    const parsed = JSON.parse(raw);
    if (Array.isArray(parsed) && parsed.length) return parsed;
    return JSON.parse(JSON.stringify(DEFAULT_MATERIALS));
  } catch (e) {
    return JSON.parse(JSON.stringify(DEFAULT_MATERIALS));
  }
}
function saveMaterials(list) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
}

/* state */
let materials = loadMaterials();

/* utility helpers */
function findMaterial(name) {
  return materials.find(m => m.name.toLowerCase() === name.toLowerCase());
}
function calcServingsForMaterial(mat) {
  if (!mat || !mat.perServe || mat.perServe <= 0) return Infinity;
  if (mat.unit === 'g') return Math.floor(mat.quantity / mat.perServe);
  return Math.floor(mat.quantity / mat.perServe);
}
function statusByServings(s) {
  if (s < 10) return { label: 'Low', color: 'red' };
  if (s <= 30) return { label: 'Medium', color: 'yellow' };
  return { label: 'Good', color: 'green' };
}
function numberFormat(n) {
  return parseFloat(n).toLocaleString(undefined, { maximumFractionDigits: 2 });
}
function escapeHtml(str) {
  return String(str).replace(/[&<>"'`=\/]/g, s => ({ "&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;","/":"&#x2F;","`":"&#x60;","=":"&#x3D;" }[s]));
}

/* render inventory table */
function renderInventory() {
  const tbody = document.getElementById('inventoryTable');
  tbody.innerHTML = '';
  materials.forEach((m, idx) => {
    const servings = calcServingsForMaterial(m);
    const status = statusByServings(servings);
    const maxRef = m.maxRef || Math.max(m.quantity, 1);
    const pct = Math.min(100, Math.round((m.quantity / maxRef) * 100));
    const progressColor = status.color === 'red' ? 'bg-red-500' : status.color === 'yellow' ? 'bg-yellow-400' : 'bg-green-500';

    tbody.insertAdjacentHTML('beforeend', `
      <tr class="hover:bg-gray-50">
        <td class="px-4 py-3">
          <div class="font-semibold text-gray-800">${escapeHtml(m.name)}</div>
          <div class="text-xs text-gray-500 mt-1">${escapeHtml(m.unit)}</div>
        </td>

        <td class="px-4 py-3">
          <input type="number" min="0" step="any" value="${m.quantity}" class="w-24 border rounded px-2 py-1" onchange="updateQty(${idx}, this.value)">
        </td>

        <td class="px-4 py-3">${escapeHtml(m.unit)}</td>

        <td class="px-4 py-3">₱${numberFormat(m.price)}</td>

        <td class="px-4 py-3 text-xs">${m.unit === 'g' ? m.perServe + ' g/serve' : m.perServe + ' ' + m.unit + '/serve'}</td>

        <td class="px-4 py-3">
          <div class="${status.color === 'red' ? 'text-red-600' : status.color === 'yellow' ? 'text-yellow-600' : 'text-green-700'} font-bold">${servings}</div>
          <div class="w-36 h-2 bg-gray-200 rounded mt-2 overflow-hidden">
            <div style="width:${pct}%;" class="${progressColor} h-2"></div>
          </div>
        </td>

        <td class="px-4 py-3 text-center">
          <div class="flex items-center justify-center gap-3">
            <button onclick="openEditModal(${idx})" class="text-blue-600 hover:text-blue-800 font-semibold">Edit</button>
            <button onclick="deleteMaterial(${idx})" class="text-red-600 hover:text-red-800 font-semibold">Delete</button>
          </div>
        </td>
      </tr>
    `);
  });

  document.getElementById('totalMaterials').innerText = `Materials: ${materials.length}`;
  const totalServ = materials.reduce((acc, m) => {
    const s = calcServingsForMaterial(m);
    return acc + (isFinite(s) ? s : 0);
  }, 0);
  document.getElementById('totalServings').innerText = `Sum possible servings (all mats): ${totalServ}`;

  saveMaterials(materials);
  renderMenuGroups();
  renderMenuCards();
  renderMenuTable();
}

/* render collapsible menu groups with ingredient rows */
function renderMenuGroups() {
  const container = document.getElementById('menuGroups');
  container.innerHTML = '';
  Object.entries(RECIPES).forEach(([menuName, ingMap], menuIndex) => {
    // compute servings per ingredient for the group
    let htmlIngredients = '';
    for (const [ing, perUse] of Object.entries(ingMap)) {
      const mat = findMaterial(ing);
      let servingsAvail = 0;
      let missing = false;
      if (!mat) { missing = true; servingsAvail = 0; }
      else {
        // special: garlic bits stored in g with perServe in grams; if perUse appears >1 and mat.unit=='g' keep consistent
        if (mat.unit === 'g') {
          // many recipes list garlic bits as grams (e.g. 5), while mat.perServe is in grams also
          servingsAvail = Math.floor(mat.quantity / mat.perServe);
        } else {
          // if recipe perUse is in same unit as mat.unit use that; otherwise attempt direct division
          servingsAvail = Math.floor(mat.quantity / perUse);
        }
      }
      const badge = missing ? `<span class="text-xs text-red-600 font-semibold">Missing</span>` :
                    `<span class="text-xs ${servingsAvail < 10 ? 'text-red-600' : servingsAvail <=30 ? 'text-yellow-600' : 'text-green-700'} font-semibold">${servingsAvail}</span>`;
      // ingredient line clickable to jump to inventory row
      htmlIngredients += `
        <div class="flex items-center justify-between py-2 border-b last:border-b-0">
          <div class="text-sm">
            <button class="text-left text-sm text-gray-800 hover:underline collapse-toggle" onclick="jumpToMaterial('${escapeHtml(ing)}')">${escapeHtml(ing)}</button>
            <div class="text-xs text-gray-500">use ${perUse} ${mat && mat.unit ? mat.unit : ''} per serve</div>
          </div>
          <div class="text-right">${badge}</div>
        </div>`;
    }

    container.insertAdjacentHTML('beforeend', `
      <div class="bg-white rounded-lg shadow-sm p-3 border">
        <div class="flex items-center justify-between">
          <div>
            <h4 class="font-semibold text-gray-800">${escapeHtml(menuName)}</h4>
            <div class="text-xs text-gray-500 mt-1">${Object.keys(ingMap).join(', ')}</div>
          </div>
          <div class="flex items-center gap-2">
            <button class="text-sm bg-gray-100 px-2 py-1 rounded" onclick="toggleCollapse('menu-${menuIndex}')">Toggle</button>
          </div>
        </div>

        <div id="menu-${menuIndex}" class="mt-3 hidden">
          ${htmlIngredients}
        </div>
      </div>
    `);
  });
}

/* toggle collapse helper */
function toggleCollapse(id) {
  const el = document.getElementById(id);
  if (!el) return;
  el.classList.toggle('hidden');
}

/* jump to inventory row and highlight */
function jumpToMaterial(name) {
  const rows = document.querySelectorAll('#inventoryTable tr');
  let foundIndex = -1;
  for (let i = 0; i < materials.length; i++) {
    if (materials[i].name.toLowerCase() === name.toLowerCase()) { foundIndex = i; break; }
  }
  if (foundIndex === -1) {
    alert(`${name} not found in inventory. Add it first to edit stock.`);
    return;
  }
  // scroll and flash highlight
  const row = rows[foundIndex];
  if (!row) return;
  row.scrollIntoView({ behavior: 'smooth', block: 'center' });
  row.classList.add('ring-2','ring-red-200');
  setTimeout(()=> row.classList.remove('ring-2','ring-red-200'), 1400);
}

/* Menu overview cards (right) */
function renderMenuCards() {
  const container = document.getElementById('menuCards');
  container.innerHTML = '';
  Object.entries(RECIPES).forEach(([menuName, ingMap]) => {
    let servingsList = [];
    let missing = [];
    for (const [ing, perUse] of Object.entries(ingMap)) {
      const mat = findMaterial(ing);
      if (!mat) { servingsList.push(0); missing.push(ing); continue; }
      let s;
      if (mat.unit === 'g') s = Math.floor(mat.quantity / mat.perServe);
      else s = Math.floor(mat.quantity / perUse);
      servingsList.push(s);
    }
    const possible = missing.length ? 0 : Math.min(...servingsList);
    const status = statusByServings(possible);
    container.insertAdjacentHTML('beforeend', `
      <div class="bg-white rounded-xl p-3 shadow-sm border">
        <div class="flex items-start justify-between">
          <div>
            <h4 class="font-bold text-gray-800">${escapeHtml(menuName)}</h4>
            <div class="text-xs text-gray-500 mt-1">${Object.keys(ingMap).slice(0,4).join(', ')}${Object.keys(ingMap).length>4 ? '...' : ''}</div>
          </div>
          <div class="text-right">
            <div class="${status.color === 'red' ? 'text-red-600' : status.color === 'yellow' ? 'text-yellow-600' : 'text-green-700'} font-bold text-lg">${possible}</div>
            <div class="text-xs text-gray-500 mt-1">servings</div>
          </div>
        </div>
        <div class="mt-3 flex items-center justify-between gap-3">
          <div>${missing.length ? `<span class="text-xs text-red-600 font-semibold">Missing: ${missing.length}</span>` : ''}</div>
          <div>
            ${status.label === 'Low' ? `<span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">⚠️ Low</span>` :
             status.label === 'Medium' ? `<span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">⚠️ Medium</span>` :
             `<span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">OK</span>`}
          </div>
        </div>
      </div>
    `);
  });
}

/* menu table (detailed) */
function renderMenuTable() {
  const tbody = document.getElementById('menuTable');
  tbody.innerHTML = '';
  Object.entries(RECIPES).forEach(([menuName, ingMap]) => {
    let servingsList = [];
    let missing = [];
    let costSum = 0;
    for (const [ing, perUse] of Object.entries(ingMap)) {
      const mat = findMaterial(ing);
      if (!mat) { servingsList.push(0); missing.push(ing); continue; }
      // cost calculation: if mat.unit === 'g' and perUse given in grams, ensure units align
      let costContribution;
      if (mat.unit === 'g') {
        // mat.price is price per gram in our defaults (we set small price per gram value)
        costContribution = perUse * mat.price;
        servingsList.push(Math.floor(mat.quantity / mat.perServe));
      } else {
        costContribution = perUse * mat.price;
        servingsList.push(Math.floor(mat.quantity / perUse));
      }
      costSum += costContribution;
    }
    const possible = missing.length ? 0 : Math.min(...servingsList);
    const status = statusByServings(possible);
    tbody.insertAdjacentHTML('beforeend', `
      <tr class="hover:bg-gray-50">
        <td class="px-4 py-3 font-semibold">${escapeHtml(menuName)}</td>
        <td class="px-4 py-3 text-xs text-gray-500">${Object.keys(ingMap).join(', ')}</td>
        <td class="px-4 py-3">
          <div class="${status.color === 'red' ? 'text-red-600' : status.color === 'yellow' ? 'text-yellow-600' : 'text-green-700'} font-bold">${possible}</div>
        </td>
        <td class="px-4 py-3">₱${numberFormat(costSum)}</td>
        <td class="px-4 py-3">${status.label === 'Low' ? '<span class="text-red-600 font-semibold">Low</span>' : status.label === 'Medium' ? '<span class="text-yellow-700 font-semibold">Medium</span>' : '<span class="text-green-700 font-semibold">Good</span>'}</td>
      </tr>
    `);
  });
}

/* CRUD handlers */
function updateQty(idx, value) {
  const v = parseFloat(value);
  if (isNaN(v) || v < 0) return;
  materials[idx].quantity = v;
  saveMaterials(materials);
  renderInventory();
}
function deleteMaterial(idx) {
  if (!confirm(`Delete "${materials[idx].name}"?`)) return;
  materials.splice(idx, 1);
  saveMaterials(materials);
  renderInventory();
}

/* modal add/edit */
const modal = document.getElementById('materialModal');
const form = document.getElementById('materialForm');
function openAddModal() {
  document.getElementById('modalTitle').innerText = 'Add Material';
  document.getElementById('editIndex').value = '';
  form.reset();
  modal.classList.remove('hidden'); modal.classList.add('flex');
}
function openEditModal(idx) {
  const mat = materials[idx];
  document.getElementById('modalTitle').innerText = 'Edit Material';
  document.getElementById('editIndex').value = idx;
  document.getElementById('matName').value = mat.name;
  document.getElementById('matUnit').value = mat.unit;
  document.getElementById('matQty').value = mat.quantity;
  document.getElementById('matPerServe').value = mat.perServe;
  document.getElementById('matPrice').value = mat.price;
  document.getElementById('matMaxRef').value = mat.maxRef || '';
  modal.classList.remove('hidden'); modal.classList.add('flex');
}
function closeModal() {
  modal.classList.add('hidden'); modal.classList.remove('flex');
  form.reset();
  document.getElementById('editIndex').value = '';
}

form.addEventListener('submit', function(e) {
  e.preventDefault();
  const idxVal = document.getElementById('editIndex').value;
  const name = document.getElementById('matName').value.trim();
  const unit = document.getElementById('matUnit').value.trim();
  const quantity = parseFloat(document.getElementById('matQty').value) || 0;
  const perServe = parseFloat(document.getElementById('matPerServe').value) || 0;
  const price = parseFloat(document.getElementById('matPrice').value) || 0;
  const maxRefInput = document.getElementById('matMaxRef').value;
  const maxRef = maxRefInput ? parseInt(maxRefInput) : undefined;
  if (!name) return alert('Please provide a material name.');

  const payload = { name, unit, quantity, perServe, price, maxRef };
  if (idxVal !== '') {
    materials[parseInt(idxVal)] = payload;
  } else {
    materials.push(payload);
  }
  saveMaterials(materials);
  renderInventory();
  closeModal();
});


/* initial load */
materials = loadMaterials();
renderInventory();

</script>

