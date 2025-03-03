const fs = require( 'fs' );
const archiver = require( 'archiver' );

// Create a new zip file
const output = fs.createWriteStream( 'woodemo-theme.zip' );
const archive = archiver( 'zip', { zlib: { level: 9 } } );

// Add files and directories to the zip file
archive.directory( 'woo-demo-theme/', 'woodemo-theme' );

// Finalize the zip file
archive.pipe( output );
archive.finalize();
