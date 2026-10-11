const { test } = require('node:test')
const assert = require('node:assert/strict')
const { checkAudit } = require('./npm-audit.cjs')

function report(vulnerabilities) {
  return { auditReportVersion: 2, metadata: {}, vulnerabilities }
}
const accepted = { url: 'https://github.com/advisories/GHSA-vfj7-8cjw-p6xm' }
const unknown = { url: 'https://github.com/advisories/GHSA-new-advisory' }

test('filters approved advisory chains, but blocks mixed and new findings', () => {
  const result = checkAudit(report({
    braces: { via: [accepted] },
    tailwind: { via: ['braces'] },
    mixed: { via: ['braces', unknown] },
    other: { via: [unknown] },
  }), '2026-10-09')
  assert.deepEqual(result, { blocked: ['mixed', 'other'], deferred: 2 })
})

test('exceptions expire on the review date', () => {
  assert.deepEqual(checkAudit(report({ braces: { via: [accepted] } }), '2026-11-08').blocked, ['braces'])
})

test('fails closed for missing dependencies and cycles', () => {
  assert.deepEqual(checkAudit(report({
    missing: { via: ['absent'] },
    cycle: { via: ['cycle'] },
  }), '2026-10-09').blocked, ['missing', 'cycle'])
})

test('rejects audit errors and invalid reports', () => {
  assert.throws(() => checkAudit({ error: {} }))
  assert.throws(() => checkAudit({}))
})
