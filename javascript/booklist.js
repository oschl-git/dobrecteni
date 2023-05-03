// let books = array of books from the database
// let phpSelf = refers to this page

// Node references:
const bookTable = document.querySelector('#book-table');

const containers = {
	details: document.querySelector('#details-container'),
	edit: document.querySelector('#edit-container'),
	delete: document.querySelector('#delete-container'),
	add: document.querySelector('#add-container'),
}

const detailsFields = {
	name: document.querySelector('#details-name'), 
	author: document.querySelector('#details-author'), 
	genre: document.querySelector('#details-genre'), 
	read: document.querySelector('#details-read'), 
	rating: document.querySelector('#details-rating'), 
	isbn: document.querySelector('#details-isbn'), 
	datePublished: document.querySelector('#details-date'), 
	pages: document.querySelector('#details-pages'), 
	notes: document.querySelector('#details-notes'),
	cover: document.querySelector('#details-cover'),
};

const editFields = {
	name: document.querySelector('#edit-name'), 
	author: document.querySelector('#edit-author'), 
	genre: document.querySelector('#edit-genre'), 
	read: document.querySelector('#edit-read'), 
	rating: document.querySelector('#edit-rating'), 
	isbn: document.querySelector('#edit-isbn'), 
	datePublished: document.querySelector('#edit-date'), 
	pages: document.querySelector('#edit-pages'), 
	notes: document.querySelector('#edit-notes'),
	id: document.querySelector('#edit-book-id'),
};

const deleteFields = {
	name: document.querySelector('#delete-name'), 
	id: document.querySelector('#delete-book-id'),
};

// Variables:
let currentBook;


onLoadActions();


// Executes functions on page load.
function onLoadActions() {
	showBoooksFromArray(books);
}

// Displays books from the provided array on the page.
function showBoooksFromArray(array) {
	let index = 0;
	for (const book of array) {
		showBook(book, index);
		index++;
	}
}

// Displays a single book on the page.
function showBook(book, index) {
	bookTable.innerHTML += `
		<tr>
			<td>` + String(book['name']) + `</td>
			<td>` + String(book['author']) + `</td>
			<td>` + String(book['genre']) + `</td>
			<td>` + String(book['rating']) + `</td>
			<td>` + String(book['read']) + `</td>
			<td>
				<button type="button" data-book-index="` + index + `" onclick="showDetails(this)">Details</button>
				<button type="button" data-book-index="` + index + `" onclick="editBook(this)">Edit</button>
				<button type="button" data-book-index="` + index + `" onclick="deleteBook(this)">Delete</button>
			</td>
		</tr>`;
}

// Shows the details container, fills it with correct data. If the clicked element contains the
// 'data-book-index' attribute, its value is used to change the current book.
function showDetails(clickedElement = null) {
	if (clickedElement != null) currentBook = books[clickedElement.getAttribute('data-book-index')];

	detailsFields['name'].innerHTML = currentBook['name'];
	detailsFields['author'].innerHTML = currentBook['author'];
	detailsFields['genre'].innerHTML = currentBook['genre'];
	detailsFields['read'].innerHTML = currentBook['read'];
	detailsFields['rating'].innerHTML = currentBook['rating'];
	detailsFields['isbn'].innerHTML = currentBook['isbn'];
	detailsFields['datePublished'].innerHTML = currentBook['date_published'];
	detailsFields['pages'].innerHTML = currentBook['pages'];
	detailsFields['notes'].innerHTML = currentBook['notes'];

	detailsFields['cover'].setAttribute('src', getCoverSrcFromIsbn(currentBook['isbn'], 'M'));

	showContainer('details');
}

// Shows the edit container, fills it with correct data. If the clicked element contains the
// 'data-book-index' attribute, its value is used to change the current book.
function editBook(clickedElement) {
	if (clickedElement != null) currentBook = books[clickedElement.getAttribute('data-book-index')];
	
	editFields['name'].value = currentBook['name'];
	editFields['author'].value = currentBook['author'];
	editFields['genre'].value = currentBook['genre'];
	editFields['author'].value = currentBook['author'];
	editFields['read'].checked = currentBook['read'];
	editFields['rating'].value = currentBook['rating'];
	editFields['isbn'].value = currentBook['isbn'];
	editFields['datePublished'].value = currentBook['date_published'];
	editFields['pages'].value = currentBook['pages'];
	editFields['notes'].value = currentBook['notes'];

	editFields['id'].setAttribute('value', currentBook['id']);
	
	showContainer('edit');
}

// Shows the delete container, fills it with correct data. If the clicked element contains the
// 'data-book-index' attribute, its value is used to change the current book.
function deleteBook(clickedElement) {
	if (clickedElement != null) currentBook = books[clickedElement.getAttribute('data-book-index')];

	deleteFields['name'].innerHTML = currentBook['name'];
	deleteFields['id'].setAttribute('value', currentBook['id']);

	showContainer('delete');
}

// Shows the add container, fills it with correct data. If the clicked element contains the
// 'data-book-index' attribute, its value is used to change the current book.
function addBook(clickedElement) {
	showContainer('add');
}

// Changes the visibility of all containers to none, except the provided one, which it changes to
// 'block'.
function showContainer(name) {
	hideAllContainers();
	containers[name].style.display = 'block';
}

// Changes the visibility of all containers to none.
function hideAllContainers() {
	for (const container of Object.values(containers)) {
		container.style.display = 'none';
	}
}

// Returns the cover for the provided ISBN, if it exist. Size can be specified. Possible values: 
// 'S', 'M', 'L'.
function getCoverSrcFromIsbn(isbn, size) {
	return 'https://covers.openlibrary.org/b/isbn/' + isbn + '-' + size + '.jpg?default=false';
}