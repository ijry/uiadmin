export function writeFileDown(content, fileName, option = {}) {
  var a = document.createElement('a')
  var url = window.URL.createObjectURL(new Blob([content],
    { type: (option.type || mimeType.txt) + ';charset=' + (option.encoding || 'utf-8') }))
  a.href = url
  a.target = '_blank'
  a.download = fileName || 'file.txt'
  a.click()
  window.URL.revokeObjectURL(url)
}

export const mimeType = {
  txt: 'text/plain',
  sql: 'text/plain',
  md: 'text/markdown',
  pdf: 'application/pdf',
  xls: 'application/vnd.ms-excel',
  js: 'application/x-javascript',
  html: 'text/html'
}
