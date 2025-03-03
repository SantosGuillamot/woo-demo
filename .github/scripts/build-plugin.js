const fs = require( 'fs' );
const archiver = require( 'archiver' );

// Create a new zip file
const output = fs.createWriteStream( 'woo-demo.zip' );
const archive = archiver( 'zip', { zlib: { level: 9 } } );

// Add files and directories to the zip file
archive.directory( './woo-demo-plugin/lib/', 'lib' );
archive.directory( './woo-demo-plugin/src/', 'src' );
archive.directory( './woo-demo-plugin/build/', 'build' );
archive.file( 'README.md', { name: 'README.md' } );
archive.file( './woo-demo-plugin/woodemo.php', { name: 'woodemo.php' } );

// Finalize the zip file
archive.pipe( output );
archive.finalize();
