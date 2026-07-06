const fs = require('fs');
const path = require('path');

function walk(dir) {
  const results = [];
  const list = fs.readdirSync(dir, {withFileTypes: true});
  for (const item of list) {
    const full = path.join(dir, item.name);
    if (item.isDirectory()) results.push(...walk(full));
    else if (item.name.endsWith('.blade.php')) results.push(full);
  }
  return results;
}

const files = walk('resources/views');
let count = 0;

for (const file of files) {
  let content = fs.readFileSync(file, 'utf-8');
  let original = content;

  // Pattern 1: Empty table state with ti-zoom-question and "Belum ada data"
  content = content.replace(
    /<i class="ti ti-zoom-question text-muted" style="font-size: 3rem;"><\/i>\s*<p class="mt-3 mb-0 text-muted">Belum ada[^<]*<\/p>/g,
    '@include(\'components.empty-state\')'
  );

  // Pattern 2: Simple "Belum ada data" with colspan
  content = content.replace(
    /<td colspan="\d+" class="text-center py-5 text-muted">Belum ada data[^<]*<\/td>/g,
    '<td colspan="$1" class="text-center py-5">@include(\'components.empty-state\')</td>'
  );

  if (content !== original) {
    fs.writeFileSync(file, content, 'utf-8');
    console.log('Updated: ' + file);
    count++;
  }
}
console.log('Total files updated: ' + count);