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
  
  // Only replace btn-subtle-primary px-3 (filter reset buttons)
  content = content.replace(/class="btn btn-subtle-primary px-3"/g, 'class="btn btn-white border px-3"');
  
  // Only replace btn-primary that is inside filter row context (has ti-filter icon nearby)
  // More targeted: replace btn-primary that appears in filter sections
  content = content.replace(/class="btn btn-primary"><i class="ti ti-filter">/g, 'class="btn btn-white border"><i class="ti ti-filter">');
  
  if (content !== original) {
    fs.writeFileSync(file, content, 'utf-8');
    console.log('Updated: ' + file);
    count++;
  }
}
console.log('Total files updated: ' + count);