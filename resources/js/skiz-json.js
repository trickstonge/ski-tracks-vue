import { readdir, readFile, writeFile } from 'node:fs/promises';
import { parseSkizFile } from 'skiz-parser';

(async () => {
    const filePath = process.argv.slice(2)[0];
    const contents = await readFile(filePath);
    const result = await parseSkizFile(contents);

    delete result.batteryUsage;
    delete result.relativeAltitude;
    result.trackNodes = result.trackNodes.slice(0, 1);

    const jsonFilePath = filePath.replace('skiz', 'json').replace('.zip', '.json');
    await writeFile(jsonFilePath, JSON.stringify(result, null, 2));

    console.log(jsonFilePath);
  }
)();